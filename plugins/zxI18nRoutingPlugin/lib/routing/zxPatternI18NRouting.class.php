<?php

/*
 * This file is part of the zxI18nRoutingPlugin package.
 * (c) ZAANAX www.zaanax.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this plugin.
 */

/**
 * zxPatternI18NRouting class controls the generation and parsing of I18N URLs.
 *
 * It parses and generates URLs by delegating the work to an array of sfRoute objects.
 *
 * @package    zxI18nRoutingPlugin
 * @author     Zbigniew "ZAAN" Niedzielski <z.niedzielski@zaanax.com>
 * @version    SVN: $Id$
 */
class zxPatternI18NRouting extends sfPatternRouting
{

  /**
   * Initializes this Routing.
   *
   * Available options:
   *
   *  * use_cultures:                     An array of cultures for which routes should be created (empty array as default)
   *  * culture_into_url:                 If :sf_culture should be placed automatically for translated routes (true by default)
   *
   * @see sfPatternRouting
   */
  public function initialize(sfEventDispatcher $dispatcher, sfCache $cache = null, $options = array())
  {
    $options = array_merge(array(
      'use_cultures'                => array(),
      'culture_into_url'            => false,
    ), $options);

    parent::initialize($dispatcher, $cache, $options);
  }

  /**
   * @see sfPatternRouting
   */
  public function loadConfiguration()
  {
    if ($this->options['load_configuration'] && $config = $this->getConfigFilename())
    {
      include($config);
      
      // It is needed to translate yml  defined routes
      foreach ($this->routes as $name => $route)
      {
        // BC for 1.2 version of plugin
        $route->setDefaultOptions(array_merge($route->getDefaultOptions(),array('insertAfter'=>true)));
        $this->connect($name,$route);
      }
    }

    parent::loadConfiguration();
  }  
  
  /**
   * Adds a new route at the end of the current list of routes and creates additional
   * routes for cultures specified in "use_cultures" factories.yml routing params
   *
   * @see sfPatternRouting
   */
  public function connect($name, $route)
  {
    if ($route instanceOf sfRouteCollection)
    {
      foreach ($route as $name => $route)
      {
        $this->connect($name, $route);
      }
    }

    if (!isset($this->routes[$name]))
    {
      parent::connect($name,$route);
    }

    $options = $route->getOptions();

    if (!isset($options['zxI18NRouting']))
    {
      if (isset($this->options['use_cultures']) && is_array($this->options['use_cultures']))
      {
        $pattern = $route->getPattern();
        $defaults = $route->getDefaults();
        $requirements = $route->getRequirements();
        $route_class = get_class($route);
        $options['zxI18NRouting'] = true;
        $used_patterns = array($pattern);

        // fix for sfPropelRoute
        if (isset($options['model']) && (false != strpos($options['model'],'Peer')))
        {
          $options['model'] = substr($options['model'],0,strpos($options['model'],'Peer'));
        }

        // for BC with plugin version of 1.2
        $last_route_name = $name;
        $defaultOptions = $route->getDefaultOptions();
        
        // creates not translated route, but with i18n possibility
        if ($this->options['culture_into_url'])
        {
          if (false == strpos($pattern,':sf_culture'))
          {
            $not_translated_pattern = ":sf_culture".$pattern;
            
            if (!isset($defaultOptions['insertAfter'])) 
            {
              $this->connect($name."_ntr", new $route_class($not_translated_pattern, $defaults, $requirements, $options));
            }
            else
            {
              $this->insertRouteAfter($last_route_name, $name."_ntr", new $route_class($not_translated_pattern, $defaults, $requirements, $options));
              $last_route_name = $name."_ntr";
            }
            
            $used_patterns[] = $not_translated_pattern;
          }
        }

        // creates translated routes
        foreach ($this->options['use_cultures'] as $culture)
        {
          $defaults['sf_culture'] = $culture;
          $translated_pattern = $this->translatePattern($culture,$pattern,$route);

          if (!in_array($translated_pattern,$used_patterns))
          {
            if (!isset($defaultOptions['insertAfter'])) 
            {
              $this->connect($name."_".$culture, new $route_class($translated_pattern, $defaults, $requirements, $options));
            }
            else
            {
              $this->insertRouteAfter($last_route_name,$name."_".$culture, new $route_class($translated_pattern, $defaults, $requirements, $options));
              $last_route_name = $name."_".$culture;
            }
            $used_patterns[] = $translated_pattern;
          }
        }
      }
    }

    return $this->routes;
  }

  /**
   * Translates pattern to specific language on base of I18N.
   *
   * @param string $culture Culture for which translation will be made
   * @param string $pattern Not translated pattern
   * @param array $options Route
   * @return string $pattern Translated pattern
   *
   */
  public function translatePattern($culture, $pattern, $route)
  {
    if (sfConfig::get('sf_i18n') && sfContext::hasInstance())
    {
      $i18n = sfContext::getInstance()->getI18N();
      $cloned_i18n = clone $i18n;
      $cloned_i18n->setCulture($culture);

      $pattern_parts = $route->getTokens();
      foreach ($pattern_parts as $element => $part)
      {
        if ($part[0]=='text')
        {
          $pattern_parts[$element][2] = $cloned_i18n->__($part[2],array(),'routing');
        }
      }
      
      $new_pattern = ''; 
      foreach ($pattern_parts as $part)
      {
        $new_pattern .= $part[2];
      }
      
     $pattern = ($new_pattern != $pattern) ? $new_pattern : $pattern;

      if ($this->options['culture_into_url'])
      {
        if (false == strpos($pattern,':sf_culture'))
        {
          $pattern = ":sf_culture".$pattern;
        }
      }
    }

    return $pattern;
  }


 /**
   * Adds a new route after a given one in the current list of routes.
   *
   * @see connect
   */
  public function insertRouteAfter($pivot, $name, $route)
  {
    if (!isset($this->routes[$pivot]))
    {
      throw new sfConfigurationException(sprintf('Unable to insert route "%s" before inexistent route "%s".', $name, $pivot));
    }

    $routes = $this->routes;
    $this->routes = array();
    $newroutes = array();

    foreach ($routes as $key => $value)
    {
      $newroutes[$key] = $value;
      if ($key == $pivot)
      {
        $this->connect($name, $route);
        $newroutes = array_merge($newroutes, $this->routes);
      }
    }

    $this->routes = $newroutes;
  }
  
  

  /**
   * @see sfPatternRouting
   */
  public function generate($name, $params = array(), $absolute = false)
  {

    // fetch from cache
    if (null !== $this->cache)
    {
      $cacheKey = 'generate_'.$name.'_'.md5(serialize(array_merge($this->defaultParameters, $params))).'_'.md5(serialize($this->options['context']));
      if ($this->options['lookup_cache_dedicated_keys'] && $url = $this->cache->get('symfony.routing.data.'.$cacheKey))
      {
        return $this->fixGeneratedUrl($url, $absolute);
      }
      elseif (isset($this->cacheData[$cacheKey]))
      {
        return $this->fixGeneratedUrl($this->cacheData[$cacheKey], $absolute);
      }
    }

    if ($name)
    {
      // named route
      if (!isset($this->routes[$name]))
      {
        throw new sfConfigurationException(sprintf('The route "%s" does not exist.', $name));
      }

      $culture = sfContext::hasInstance() ? sfContext::getInstance()->getUser()->getCulture() : sfConfig::get('sf_default_culture');

      // culture is specified, translated route exists
      if (isset($params['sf_culture']) && isset($this->routes[$name."_".$params['sf_culture']]))
      {
        $route = $this->routes[$name."_".$params['sf_culture']];
      }
      // culture is specified, not translated route with i18n exists
      elseif ((isset($params['sf_culture']) || !isset($this->routes[$name."_".$culture])) && isset($this->routes[$name."_ntr"]))
      {
        $route = $this->routes[$name."_ntr"];
        $params['sf_culture'] = !isset($params['sf_culture']) ? $culture : $params['sf_culture'];
      }
      // culture is not specified, route for current user culture exists
      elseif (isset($this->routes[$name."_".$culture]) && !isset($params['sf_culture']))
      {
        $route = $this->routes[$name."_".$culture];
      }
      // default not translated route
      else
      {
        $route = $this->routes[$name];      $this->ensureDefaultParametersAreSet();
      }
    }
    else
    {
      // find a matching route
      if (false === $route = $this->getRouteThatMatchesParameters($params, $this->options['context']))
      {
        throw new sfConfigurationException(sprintf('Unable to find a matching route to generate url for params "%s".', is_object($params) ? 'Object('.get_class($params).')' : str_replace("\n", '', var_export($params, true))));
      }
    }

    $url = $route->generate($params, $this->options['context'], $absolute);

    // store in cache
    if (null !== $this->cache)
    {
      if ($this->options['lookup_cache_dedicated_keys'])
      {
        $this->cache->set('symfony.routing.data.'.$cacheKey, $url);
      }
      else
      {
        $this->cacheChanged = true;
        $this->cacheData[$cacheKey] = $url;
      }
    }

    return $this->fixGeneratedUrl($url, $absolute);
  }
}

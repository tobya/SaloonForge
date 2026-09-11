<?php

  namespace Tobya\SaloonForge\Services;

  class ConfigService
  {

      const string EmptyConfigValue = 'THIS IS A MISSING VALUE';
      public function __construct(

          public string $integration

      )
      {
      }

      /**
       * Retrieve a config value from the correct integration
       * in the saloonforge.php config file.
       * @param string $string
       * @return mixed
       */
      public function Config(string $string) : mixed
      {

          $config_path = 'saloonforge.integrations.' . $this->integration ;
          $integration_config_value = config($config_path . '.' . $string,self::EmptyConfigValue);
      
          // Value does not exist, pull it from the default.
          if ($integration_config_value === self::EmptyConfigValue) {
              $config_path = 'saloonforge.integrations.Default' ;
              $integration_config_value = config($config_path . '.' . $string);
          }
       
          return $integration_config_value;
      }

      public function get($string) : mixed
      {
          return $this->Config($string);
      }

  }

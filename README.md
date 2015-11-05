Rest-Api Bundle
===============

## Register the bundle

app/AppKernel.php
```php
public function registerBundles()
{
    $bundles = array(
        ...
        new Xima\RestApiBundle\XimaRestApiBundle(),
        ...
    );
    
    ...
}
```

## Configuration for single API-Key

app/config/parameters.yml: 
```yaml            
parameters:
    api_key: YourSecretApiKey
```

app/config/config.yml: 
```yaml            
parameters:
    api_key_user_provider.class: Xima\RestApiBundle\Security\ApiKeyUserProvider
    api_key_user_provider.key: "%api_key%"
    
services:
    api_key_user_provider:
        class: "%api_key_user_provider.class%"
        arguments: ["%api_key_user_provider.key%"]
    api_key_authenticator:
        class:     Xima\RestApiBundle\Security\ApiKeyAuthenticator
        arguments: [@api_key_user_provider]
```

## Configuration for multiple users with private API-Keys including ApiUserAdmin

app/config/config.yml: 
```yaml
doctrine:
    orm:
        entity_managers:
            default:
                connection: default
                mappings:
                    XimaRestApiBundle: ~
                    
parameters:
    api_key_user_provider.class: Xima\RestApiBundle\Security\ApiKeyUserProvider
    
services:
    api_key_user_provider:
        class: "%api_key_user_provider.class%"
        arguments: [@doctrine.orm.default_entity_manager]
    api_key_authenticator:
        class:     Xima\RestApiBundle\Security\ApiKeyAuthenticator
        arguments: [@api_key_user_provider]
```

AppBundle/Resources/config/admin_[XXXX].yml:  
```yaml
sonata.admin.common_xima_apiuser:
    class: Xima\RestApiBundle\Admin\ApiUserAdmin  
    tags:  
        - { name: sonata.admin, manager_type: orm, group: "REST API", label: "Benutzer" }  
    arguments:  
        - ~  
        - Xima\RestApiBundle\Entity\User\ApiUser  
        - ~  
    calls:  
        - [ setTranslationDomain, [XimaRestApiBundle]]  
```
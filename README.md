# Ant framework - a small php tool for big purpose. 

The concept - to know its place and purpose. 
The rule of this framework - to be flexible and not to be intrusive.

## Installation

Install the latest version with

```bash
$ composer require shubinmi/ant
```

## Basic Usage

index.php
```php
<?php

use Ant\Application\Application;

// Define paths of folders with configuration files
$configDirs = [
    'Config/Production'
];
if (defined('APPLICATION_ENV') && APPLICATION_ENV == 'local') {
    $configDirs[] = 'Config/Local';
}

$app = new Application();
$app->loadConfig($configDirs);
$app->run();
```

./Config/Production/router.php
```php
<?php

return [
    'router' => [
        'main'                   => [
            ['GET', 'POST'], '/[{msg}[/]]', [
                'controller' => 'Controllers\Main',
                'action'     => 'mainAction'
            ]
        ]
     ]
]
```

./Config/Local/router.php
```php
<?php

return [
    'router' => [
        // This will be rewriting rulles for path with 'main' key at Production folder
        'main'                   => [
            ['GET', 'POST'], '/[{msg}[/]]', [
                'controller' => 'Controllers\Main',
                'action'     => 'mainAction'
            ]
        ]
     ]
]
```

./Controllers/Main.php
```php
<?php

namespace Controllers;

use Ant\Application\Controller;
use Ant\Application\View;

class Main extends Controller
{
    public function mainAction()
    {
        $msg = $this->requestUriParam('msg');
        $elements = [
            // It mean that {{body}} at layout.phtml (and at other view elements) will be 
            // replaced to content from main.phtml
            'body' => [
                'path' => 'Views/main.phtml',
                'args' => ['msg' => $msg]
            ]
        ];
        
        return $this->getView()->addLayoutElements($elements);
    }
    
    private function getView()
    {
        $view = new View();
        $view->setLayoutPath('Views/layout.phtml');
        
        return $view;
    }
}

```

./Views/layout.phtml
```html
<!DOCTYPE html>
<html>

<head>

</head>

<body>
    
    {{body}}

</body>
</html>
```

./Views/main.phtml
```php
Your message = "
<?php
    /** @var string $msg */
    echo $msg;
?>
"
```
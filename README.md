# Swagger Dereferenser
---
Uses for combine splited reference swagger files to one array

## Usage
1. Put files with references as on schema
(for more detail see [here](http://azimi.me/2015/07/16/split-swagger-into-smaller-files.html))
```
swagger
├── index.yaml
├── info
│   └── index.yaml
├── definitions
│   └── index.yaml
│   └── User.yaml
└── paths
    ├── index.yaml
    ├── bar.yaml
    └── foo.yaml
```

2. Dereferense files 
````php
$swaggerSpec = Dereferenser::dereferense('swagger/index.yml');
````

3. PROFIT! 

```php
[
    'swagger' => '2.0',
    'info' => [
        'version' => '0.0.0',
        'title' => 'Simple API'
    ],
    'paths' => [
        '/foo' => [
            'get' => [
                'responses' =>[
                    200 => [
                        'description' => 'OK'
                    ]
                ]
            ]
        ],
        '/bar' => [
            'get' => [
                'responses' =>[
                    200 => [
                        'description' => 'OK'
                    ]
                ]
            ]
        ],
    ],
    'definitions' => [
        'User' => [
            'type' => 'object',
            'properties' => [
                'name' => [
                    'type' => 'string'
                ]
            ]
        ]
    ]
]
```
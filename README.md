# MODX Newsletter
![Newsletter version](https://img.shields.io/badge/version-2.0.0-blue.svg) ![MODX Extra by Oetzie.nl](https://img.shields.io/badge/checked%20by-oetzie-blue.svg) ![MODX version requirements](https://img.shields.io/badge/modx%20version%20requirement-2.4%2B-brightgreen.svg)

Newsletter is an extra to to manage subscriptions and send newsletters in MODx. This is a plugin for the Form extra https://github.com/Oetzie/Form. 

**Example:**
```
{'!Form' | snippet : [
    'plugins'               => [
        'newsletterform'        => [
            'type'                  => 'subscribe',
            'list'                  => 5,
            'data'                  => ['phone', 'answer']
        ]
    ]
]}
```
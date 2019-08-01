Hydrator
----------------------

<p align="center">
    <a href="https://travis-ci.org/SerafimArts/Hydrator">
        <img src="https://travis-ci.org/SerafimArts/Hydrator.svg?branch=master" alt="Travis CI" />
    </a>
    <a href="https://codeclimate.com/github/SerafimArts/Hydrator/test_coverage">
        <img src="https://api.codeclimate.com/v1/badges/ee98e53136071f35e4d5/test_coverage" />
    </a>
    <a href="https://codeclimate.com/github/SerafimArts/Hydrator/maintainability">
        <img src="https://api.codeclimate.com/v1/badges/ee98e53136071f35e4d5/maintainability" />
    </a>
</p>
<p align="center">
    <a href="https://packagist.org/packages/rds/hydrator">
        <img src="https://img.shields.io/badge/PHP-7.2+-ff0140.svg" alt="PHP 7.1+" />
    </a>
    <a href="https://packagist.org/packages/rds/hydrator">
        <img src="https://poser.pugx.org/rds/hydrator/version" alt="Latest Stable Version" />
    </a>
    <a href="https://packagist.org/packages/rds/hydrator">
        <img src="https://poser.pugx.org/rds/hydrator/v/unstable" alt="Latest Stable Version" />
    </a>
    <a href="https://packagist.org/packages/rds/hydrator">
        <img src="https://poser.pugx.org/rds/hydrator/downloads" alt="Total Downloads" />
    </a>
    <a href="https://raw.githubusercontent.com/SerafimArts/Hydrator/master/LICENSE.md">
        <img src="https://poser.pugx.org/rds/hydrator/license" alt="License MIT" />
    </a>
</p>

## Introduction

Hydrator library provides a simple way for converting data arrays into specific 
objects of the domain and vice versa.

## Requirements

- PHP 7.2+
- ext-json

### Configuration Loaders

- `symfony/yaml` for `Rds\Hydrator\Loader\YamlLoader`
- `ext-json` for `Rds\Hydrator\Loader\JsonLoader`
- `railt/json` for `Rds\Hydrator\Loader\Json5Loader`

## Installation

Hydrator is available as composer repository and can be installed using the 
following command in the root of your project:

```bash
$ composer require rds/hydrator
```

In order to access Hydrator classes make sure to include 
`vendor/autoload.php` in your file.

```php
require __DIR__ . '/vendor/autoload.php';
```

## Documentation

- Getting Started
    - [Quick Start](./docs/quick-start.md)
    - [License](./LICENSE.md)
    - [Changelog](./CHANGELOG.md)
- Configuration
    - Loaders
        - Yaml
        - Json
        - Json5
        - PHP
        - XML
    - Configurators
        - Simple
- Mappers
    - Accessors
    - Mutators
    - Custom
- Events
    - Hydration Events
    - Serialization Events



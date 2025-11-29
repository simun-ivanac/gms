<div id="top"></div>

# Gym Management App

## Table of contents
1. [Introduction](#introduction)
2. [Environment](#environment)
3. [Installation](#installation)
4. [Coding standards](#coding-standards)
	1. [PHP](#standards-php)
	2. [JavaScript](#standards-js)
	2. [Styles](#standards-styles)

<br>

<div id="introduction"></div>

## Introduction

Simple app intended for handling gym memberships. I use it solely for practice and improving my skills.

This is work in progress. So, imperfect and mostly basic in nature (for now). If you'd like to know more background and implementation logic behind it, feel free to check this [Notion page](https://www.notion.so/just-simun/Gym-Management-System-GMS-299ca0e180ff800bae26ccac47d8f4c5).

<div align="right">

[Top](#top)

</div>

<div id="environment"></div>

## Environment:

- Symfony: 7.3.4
- Symfony CLI: 5.15.1
- PHP: 8.2.24
- MySQL: 8
- Composer: 2.5.8
- Node: 24.10.0
- NPM: 11.6.1
- VS Code Editor

<div align="right">

[Top](#top)

</div>

<div id="installation"></div>

## Installation

Clone the repository into the root folder of your local server:

```
git clone https://github.com/simun-ivanac/gms.git
```

Rename .env-example to .env.

```
.env-example -> .env
```

> Note: the site domain I'm using in the project is ```https://gms.wip```.
> All paths are relative, so changing it to some other shouldn't hurt anything.

Install composer packages:

```
composer install
```

Setup database configuration and create one manually, or run:

```
php bin/console doctrine:database:create
```

Run migrations:

```
php bin/console doctrine:migrations:migrate
```

> In case you're pulling new changes and need to run migrations or fixtures again, make sure to nuke everything before that:
> ```
> php bin/console doctrine:schema:drop --full-database --force
> ```
> I'm not concerning myself with migration versioning at the moment, as I'd have zillion files. Now you can run *migrations:migrate* command.

Run fixtures:

```
php bin/console doctrine:fixtures:load
```

Install node modules:

```
npm install
```

Build public folder:

```
npm run build
```

If using symfony CLI for running web server, run:
```
symfony server:start
```


<div align="right">

[Top](#top)

</div>

<div id="coding-standards"></div>

## Coding standards

Here is some basic info about editor extensions and settings I used in this project.
I'm also using git pre-commit hooks that are testing for code standards (not really for all PHP, JS, and SCSS files, but only for those located in defined paths in config files).

<div id="standards-php"></div>

### PHP

Composer packages are already installed. I use <a href="https://marketplace.visualstudio.com/items?itemName=wongjn.php-sniffer" target="_blank">PHP Sniffer</a> extension for linting and formatting PHP code.
Check ```phpcs.xml``` file in case you are interested in defined rules.
Changes I added in my workspace JSON settings:

```
"phpSniffer.autoDetect": true,
"phpSniffer.run": "onType",
"[php]": {
	"editor.defaultFormatter": "wongjn.php-sniffer",
	"editor.formatOnSave": true,
},
```
<div id="standards-js"></div>

### JavaScript

Node modules are already installed. I use <a href="https://marketplace.visualstudio.com/items?itemName=dbaeumer.vscode-eslint" target="_blank">ESLint</a> extension for linting and formatting JS code.
Check ```eslint.config.mjs``` file in case you are interested in defined rules.
Changes I added in my workspace JSON settings:

```
"[javascript]": {
	"editor.defaultFormatter": "dbaeumer.vscode-eslint",
	"editor.formatOnSave": true,
},
```

<div id="standards-styles"></div>

### Styles

Node modules are already installed. I use <a href="https://marketplace.visualstudio.com/items?itemName=stylelint.vscode-stylelint" target="_blank">Stylelint</a> extension for linting and formatting SCSS code.
Check ```stylelint.config.mjs``` file in case you are interested in defined rules.
Changes I added in my workspace JSON settings:

```
"[scss]": {
	"editor.defaultFormatter": "stylelint.vscode-stylelint",
	"editor.formatOnSave": true,
	"editor.wordSeparators": "`!@%^&*()=-+[{]}\\|;:,'\"<>/?",
	"editor.codeActionsOnSave": {
		"source.fixAll.stylelint": "explicit"
	}
},
```

<br>
<br>

That's all, stop here! Happy exploring.

<div align="right">

[Top](#top)

</div>

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

This is work in progress. So, imperfect and not ready for some big things.
Still, I'm happy with the progress.

<div align="right">

[Top](#top)

</div>

<div id="environment"></div>

## Environment:

- Symfony: 7.3.4
- Symfony CLI: 5.15.1
- PHP: 8.2.24
- MySQL: 8.0.33
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

> Note: the site domain I'm using in the project is ```https://gms.wip```.
> All paths are relative, so changing it to some other shouldn't hurt anything.

Install composer packages:

```
composer install
```

> In case you change something regarding PHP classes, don't forget to dump autoload:

> ```
> composer dump-autoload -o
> ```

Install node modules:

```
npm install
```

Build public folder:

```
npm run build
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

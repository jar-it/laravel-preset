# Laravel 7.0+ Frontend Preset for JAR IT

A Laravel Frontend scaffolding preset for [JAR IT](http://jar-it.com).

## Includes

- [Tailwind CSS](https://tailwindcss.com)
- [Purgecss](https://www.purgecss.com) via [laravel-mix-purgecss](https://github.com/spatie/laravel-mix-purgecss)
- [Vue.js](https://vuejs.org)
- [ESLint](https://eslint.org)

Removes:

- Bootstrap
- jQuery

## Usage

1. Fresh install Laravel ^7.0 and cd into your app.
2. Install this preset via `composer require jar-it/laravel-preset --dev`. Laravel will automatically discover this package. No need to register the service provider.
3. Use `php artisan ui jar-it` to apply the preset.
4. Run `npm install && npm run dev`

### Config

The default `tailwind.js` configuration file included by this package simply uses the config from the Tailwind vendor files. Should you wish to make changes, you should remove the file and run `node_modules/.bin/tailwind init`, which will generate a fresh configuration file for you, which you are free to change to suit your needs.

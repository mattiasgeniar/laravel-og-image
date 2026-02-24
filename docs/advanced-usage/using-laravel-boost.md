---
title: Using Laravel Boost
weight: 8
---

This package ships with a [Laravel Boost](https://laravel.com/docs/12.x/boost) skill that helps AI agents (Claude Code, Cursor, GitHub Copilot, etc.) generate correct code when working with `spatie/laravel-og-image`.

The skill is automatically available when you have both this package and Laravel Boost installed. No additional configuration is needed. Run `php artisan boost:install` (or `php artisan boost:update`) to pick it up.

The skill covers:

- Defining OG image templates with the `<x-og-image>` Blade component
- Setting up fallback images
- Configuring screenshots (size, format, quality, drivers)
- Pre-generating and clearing images
- Customizing the page URL and action classes

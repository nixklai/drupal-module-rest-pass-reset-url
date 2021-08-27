# RESTful Reset URL Request for Drupal 9
A simple module for enabling RESTful API request of password reset URLs.

## Development / Deployment via Git
Due to various reason, dashes(-) cannot be used as the module's machine name. Thus, please clone this module as `rest_pass_reset_url` instead.

For example,
```git
git clone https://github.com/nixklai/drupal-module-rest-pass-reset-url.git rest_pass_reset_url
```

## Usage
To use this module:
1. Enable the `entity/reset_url` endpoint (e.g. visit `[YOUR DRUPAL SITE]/admin/config/services/rest` if you have REST UI installed)
2. Grant required permission(s) to role at `[YOUR DRUPAL SITE]/admin/people/permissions`

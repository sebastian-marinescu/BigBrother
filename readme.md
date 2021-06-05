# Big Brother 

Big Brother is a collection of MODX dashboard widgets that show you key insights from Google Analytics.

Originally developed by [@lossendae](https://github.com/lossendae) in 2011, Big Brother was adopted by [modmore](https://modmore.com/) in 2015 and completely rebuilt in 2021 to support Google Analytics 4 properties. 

Licensed as MIT, so you're free to use and adapt Big Brother. [Donations to support our open source work are much appreciated](https://modmore.com/extras/bigbrother/donate/), as are pull requests that improve or add to the widgets. 

## Compatibility

Big Brother v2 is currently in active development and only available from modmore.com.

We expect to release v2 on MODX.com when it's stable. After that, v1.5+ (for legacy Google Analytics 3) will be exclusively available from modmore.com as MODX.com does not support multiple release branches.

| Version | Status | Google Analytics | MODX | Available from |
| ------- | ------ | ---------------- | ---- | -------------- |
| 2.0+ (2.x branch) | Active development | 4 ("Google Analytics 4") | 2.8+, 3.0.0-alpha3+ | modmore.com |
| 1.5+ (1.x branch) | Maintenance-only | 3 ("Universal Analytics") | 2.7+, no MODX 3 | modmore.com, modx.com |
| < 1.5 | End of life | 3 ("Universal analytics") | 2.2+, no MODX 3 | modx.com |

## Usage

After installing the package, a selection of new dashboard widgets are available to you. 

- In **MODX 2.x**, navigate to system > dashboards, create a new dashboard (if you don't already have one), and add the widgets you'd like to use. Go to System > Access Control Lists, right click and update your user group, and set the dashboard you just created. Navigate to the manager dashboard and find the link to start the authorization process in the widget you just added. 
- In **MODX 3.x**, use the Add button in the top right of the manager dashboard to add any widgets you'd like to use. The manager will refresh and show you the link to start the authorization process. 

## Development

Create a `config.core.php` file in the root of the project to connect it to your MODX installation, and a `_build/build.config.php` with [oAuth credentials](https://docs.modmore.com/en/Open_Source/BigBrother/Custom_oAuth_Credentials.html). 

In `core/components/bigbrother/` run `composer install` to download PHP dependencies.

Run `php _bootstrap/index.php` to initialize settings, namespaces, and widgets into your MODX development site.

In `assets/components/bigbrother/` run `npm install` to download client dependencies. Run `npm run build:js` to build the compressed javascript files. Enable the `bigbrother.scripts_dev` system setting to use the uncompressed javascript files.

When sending pull requests, please follow the current code standards. If you'd like to know if an idea is likely to be accepted before spending time on it, please open an issue. 

## Credits

- Original v1 development by [@lossendae](https://github.com/lossendae)

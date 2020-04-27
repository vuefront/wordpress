=== VueFront PWA & SPA ===
Contributors: vuefront
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=D36ENUWPNA3KG&source=url
Tags: pwa, spa, woocommerce, amp, jamstack, ajax, vuefront, cms, vuejs, nuxt, webapp, graphql, frontend, framework, blog, ecommerce, single page application
Requires at least: 4.0.0
Tested up to: 5.2.1
Requires PHP: 5.5.0
Stable tag: 2.0.0
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Free PWA & SPA for WordPress & Woocommerce with AMP & GraphQL.

== Description ==
VueFront is a modern Frontend Framework that allows any WordPress and Woocommerce website to become a fast Single Page Application (SPA) and a Progressive Web App (PWA). 

<a href="https://wordpress.vuefront.com/" title="VueFront Web App DEMO">DEMO: VueFront Web App</a>

VueFront Web App is built on JAMstack and comes with AMP, GraphQL, Vuejs, Nuxt inside.

The VueFront WordPress Plugin is required to add a GraphQL API to your website to feed the data into the Web App. 
When installed it will provide you with a CMS Connect URL. Use it to setup your VueFront Web App as discribed in the <a href="https://vuefront.com/guide/setup.html" title="VueFront Quick Setup Guide">VueFront Quick Setup Guide</a>.

== Installation ==
1. You can either install this plugin from the WordPress Plugin Directory,
  or manually  [download the plugin](https://github.com/vuefront/wordpress/releases) and upload it through the 'Plugins > Add New' menu in WordPress
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Visit plugin's settings to get the CMS Connect URL
4. Visit the <a href="https://vuefront.com/guide/setup.html" title="VueFront setup guide">VueFront Setup Guide</a> to learn how to setup the Web App on step 2.

== Upgrade Notice ==
To upgrade simply click upgrade or upload the new version via "add new" plugin. The VueFront Plugin is smart and will apply all the changes on first load. 

== How to Use ==
Once the plugin is activated, the CMS Connect URL will function automatically, providing VueFront Web App access to the website data. 

You can now build and deploy your PWA and SPA Web App directly from the comfort of your WordPress Admin Panel.

If you have WooCommerce installed and activated, the plugin will automatically provide that data as well.

You do not need to do anything from the admin panel. It should work out-of-the-box.

Just enjoy the new PWA & SPA.
 
== Screenshots ==
1. VueFront is a Single Page Application for WordPress
2. VueFront is SEO ready and loved by google for PWA & AMP
3. VueFront works off-line with service workers
4. VueFront supports WooCommerce
5. VueFront is built on JAMstak, VueJS, Nuxt, GraphQL
6. VueFront is super fast, off-line ready, SEO ready
7. VueFront is the #1 PWA solution for WordPress and WooCommerce


== Frequently Asked Questions ==
= What do I do after I have installed the plugin? =
The plugin will provide you with a CMS Connect URL. The next step is to install the Web app via the command line. It is actually very simple to do. Just fillow <a href="https://vuefront.com/guide/setup.html" title="VueFront Setup Tutorial">setup tutorial</a>.

= When I visit the CMS Connect URL, I see this error {"errors":[{"message":"Syntax Error GraphQL (1:1) Unexpected nn1: n ^n","category":"graphql","locations":[{"line":1,"column":1}]}]} =
This is absolutly fine. It is a normal responce from GraphQL API saying you have not provided any details in you request. Continue with setting up the VueFront WebApp https://vuefront.com/guide/setup.html

= I have setup the VueFront WebApp via the command line, but the site is not working. What do I do? =
This is a very young technology and there could be version breaks. The first thing you should do is visit our <a href="https://github.com/vuefront/vuefront/issues" title="Github Issues">Github Issues</a> and search for help. If not found, create a new Issue and a developer will help you.


== Changelog ==
= 2.0.0 =
Added new admin design with Vuejs
Implemented Quick Build feature
Implemented Quick Web App deploy on Apache

= 1.2.0 =
Edited Blog Post resolver
Bug fixes

= 1.1.0 =
Added reviews total and categories to post
Added previous and next posts and datePublished
Fixed Including (or calling) javascript files included in WP core
Fixed Calling files remotely 
Fixed Generic function (and/or define) names
Fixed Including (or calling) javascript files included in WP core
Fixed Calling files remotely

= 1.0.0 =
Initial release.

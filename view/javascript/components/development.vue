<template>
  <div class="development">
    <div class="development__title">
      {{ $t('textTitle') }}
    </div>
    <div class="development__wrapper">
      <div class="development__wrapper_title">
        {{ $t('textCmsConnect') }}
      </div>
      <input
        type="text"
        class="development__cms_connect"
        :value="information.cmsConnect"
      >
      <div class="development__wrapper_text">
        {{ $t('descriptionCmsConnect') }}
      </div>
    </div>
    <div
      v-if="information.apache"
      class="development__wrapper"
    >
      <div class="development__wrapper_title">
        {{ $t('textConfigureApache') }}
      </div>
      <!-- eslint-disable vue/no-v-html -->
      <div
        class="development__wrapper_text"
        v-html="$t('descriptionConfigureApache')"
      />
      <pre>
# VueFront scripts, styles and images
RewriteCond %{REQUEST_URI} .*(_nuxt)
RewriteCond %{REQUEST_URI} !.*vuefront/_nuxt
RewriteRule ^([^?]*) vuefront/$1

# VueFront pages

# VueFront home page
RewriteRule ^$ vuefront/index.html [L]

# VueFront page if exists html file
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_URI} !.*(images|index.php|.html|admin|.js|.css|.png|.jpeg|.ico)
RewriteCond %{DOCUMENT_ROOT}/vuefront/$1.html -f
RewriteRule ^([^?]*) vuefront/$1.html [L,QSA]

# VueFront page if not exists html file
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_URI} !.*(images|index.php|.html|admin|.js|.css|.png|.jpeg|.ico)
RewriteCond %{DOCUMENT_ROOT}/vuefront/$1.html !-f
RewriteRule ^([^?]*) vuefront/200.html [L,QSA]</pre>
    </div>
  </div>
</template>
<script>
import {mapGetters} from 'vuex'
export default {
  computed: {
    ...mapGetters({
      information: 'information/get'
    })
  }
}
</script>
<i18n locale="en">
{
  "textTitle": "For Development",
  "textCmsConnect": "CMS Connect URL",
  "descriptionCmsConnect": "This is your CMS Connect URL link that shares your site data via GraphQL. When installing VueFront via the command line, you will be prompted to enter this URL. Simply copy and paste it into the command line.",
  "textConfigureApache": "Configure .htaccess",
  "descriptionConfigureApache": "For VueFront Web App to replace your current frontend, you will need to configure your .htaccess. <br>Although we do this automaticly for you, you can still customize it you yourself.<br> <ul>    <li>Turn off the VueFront app. Switch the toggle on top of the page to OFF</li>    <li>Use your text editor to edit the .htaccess file with the following rules:</li>  </ul>"
}
</i18n>
<style lang="scss">
  .development {
    &__cms_connect {
      height: 54px;
      background-color: #efeff1!important;
      padding: 12px 24px!important;
      line-height: 30px!important;
      font-family: 'Open Sans', sans-serif;
      font-size: 24px;
      font-weight: 600;
      font-stretch: normal;
      font-style: normal;
      letter-spacing: 0.24px;
      text-align: left;
      color: $black;
      margin-bottom: 20px;
      border: none!important;
      width: 100%;
    }
    &__wrapper_text {
      font-family: 'Open Sans', sans-serif;
      font-size: 18px!important;
      font-weight: normal;
      font-stretch: normal;
      font-style: normal;
      line-height: 1.56;
      letter-spacing: 0.18px;
      text-align: left;
      color: $warm-grey-two;
      ul {
        list-style-type: decimal;
        padding-left: 20px;
      }
    }
    &__title {
      font-family: 'Open Sans', sans-serif;
      font-size: 32px;
      font-weight: 600;
      font-stretch: normal;
      font-style: normal;
      line-height: 0.75;
      letter-spacing: 0.32px;
      text-align: left;
      color: $black;
      margin-bottom: 20px;
    }
    &__wrapper {
      border-radius: 3px;
      border: 1px solid $white-five;
      background-color: $white;
      padding: 50px 55px;
      margin-bottom: 60px;
      pre {
        border-radius: 3px;
        background-color: #efeff1;
        padding: 50px;

        font-family: 'Open Sans', sans-serif;
        font-size: 18px!important;
        font-weight: normal;
        font-stretch: normal;
        font-style: normal;
        line-height: 1.56;
        letter-spacing: 0.18px;
        text-align: left;
        color: $black;
      }
      &_title {
        font-family: 'Open Sans', sans-serif;
        font-size: 28px;
        font-weight: 600;
        font-stretch: normal;
        font-style: normal;
        line-height: 0.86;
        letter-spacing: 0.28px;
        text-align: left;
        color: $black;
        margin-bottom: 20px;
      }
    }
  }
</style>

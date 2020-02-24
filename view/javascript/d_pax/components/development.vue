<template>
  <div class="development">
    <div class="development__title">
      {{ $t('textTitle') }}
    </div>
    <div class="development__wrapper">
      <div class="development__wrapper_title">
        {{ $t('textCmsConnect') }}
      </div>
      <b-input-group
        class="development__cms_connect_wrapper"
      >
        <input
          type="text"
          class="development__cms_connect"
          :value="information.cmsConnect"
        >
        <b-input-group-append>
          <b-button
            v-clipboard="information.cmsConnect"
            v-clipboard:success="clipboardSuccessHandler"
            variant="success"
          >
            {{ copied ? $t('buttonCopied'): $t('buttonCopy') }}
          </b-button>
        </b-input-group-append>
      </b-input-group>

      <div class="development__wrapper_text">
        {{ $t('descriptionCmsConnect') }}
      </div>
    </div>
    <div
      v-if="information.apache"
      id="vf-apache-configure"
      class="development__wrapper"
    >
      <div

        class="development__wrapper_title"
      >
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
RewriteCond %{REQUEST_URI} !.*/vuefront/_nuxt
RewriteRule ^([^?]*) vuefront/$1

# VueFront sw.js
RewriteCond %{REQUEST_URI} .*(sw.js)
RewriteCond %{REQUEST_URI} !.*/vuefront/sw.js
RewriteRule ^([^?]*) vuefront/$1

# VueFront favicon.ico
RewriteCond %{REQUEST_URI} .*(favicon.ico)
RewriteCond %{REQUEST_URI} !.*/vuefront/favicon.ico
RewriteRule ^([^?]*) vuefront/$1


# VueFront pages

# VueFront home page
RewriteCond %{REQUEST_URI} !.*(image|.php|admin|catalog|\/img\/.*\/|wp-json|wp-admin|wp-content|checkout|rest|static|order|themes\/|modules\/|js\/|\/vuefront\/)
RewriteCond %{QUERY_STRING} !.*(rest_route)
RewriteCond %{DOCUMENT_ROOT}/vuefront/index.html -f
RewriteRule ^$ vuefront/index.html [L]

RewriteCond %{REQUEST_URI} !.*(image|.php|admin|catalog|\/img\/.*\/|wp-json|wp-admin|wp-content|checkout|rest|static|order|themes\/|modules\/|js\/|\/vuefront\/)
RewriteCond %{QUERY_STRING} !.*(rest_route)
RewriteCond %{DOCUMENT_ROOT}/vuefront/index.html !-f
RewriteRule ^$ vuefront/200.html [L]

# VueFront page if exists html file
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_URI} !.*(image|.php|admin|catalog|\/img\/.*\/|wp-json|wp-admin|wp-content|checkout|rest|static|order|themes\/|modules\/|js\/|\/vuefront\/)
RewriteCond %{QUERY_STRING} !.*(rest_route)
RewriteCond %{DOCUMENT_ROOT}/vuefront/$1.html -f
RewriteRule ^([^?]*) vuefront/$1.html [L,QSA]

# VueFront page if not exists html file
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_URI} !.*(image|.php|admin|catalog|\/img\/.*\/|wp-json|wp-admin|wp-content|checkout|rest|static|order|themes\/|modules\/|js\/|\/vuefront\/)
RewriteCond %{QUERY_STRING} !.*(rest_route)
RewriteCond %{DOCUMENT_ROOT}/vuefront/$1.html !-f
RewriteRule ^([^?]*) vuefront/200.html [L,QSA]</pre>
    </div>
    <div
      v-if="!information.apache"
      id="vf-nginx-configure"
      class="development__wrapper"
    >
      <div

        class="development__wrapper_title"
      >
        {{ $t('textConfigureNginx') }}
      </div>
      <!-- eslint-disable vue/no-v-html -->
      <div
        class="development__wrapper_text"
        v-html="$t('descriptionConfigureNginx')"
      />
      <pre>
location ~ ^((?!image|.php|admin|catalog|\/img\/.*\/|wp-json|wp-admin|wp-content|checkout|rest|static|order|themes\/|modules\/|js\/|\/vuefront\/).)*$ {
    try_files /vuefront/$uri /vuefront/$uri "/vuefront${uri}index.html" /vuefront$uri.html /vuefront/200.html;
}</pre>
    </div>
  </div>
</template>
<script>
import {mapGetters} from 'vuex'
export default {
  data() {
    return {
      copied:false
    }
  },
  computed: {
    ...mapGetters({
      information: 'information/get'
    })
  },
  methods: {
    clipboardSuccessHandler() {
      this.copied = true
      setTimeout(() => {
        this.copied = false
      }, 2000)
    }
  }
}
</script>
<i18n locale="en">
{
  "textTitle": "For Development",
  "textCmsConnect": "CMS Connect URL",
  "descriptionCmsConnect": "This is your CMS Connect URL link that shares your site data via GraphQL. When installing VueFront via the command line, you will be prompted to enter this URL. Simply copy and paste it into the command line.",
  "textConfigureApache": "Configure .htaccess",
  "descriptionConfigureApache": "For VueFront Web App to replace your current frontend, you will need to configure your .htaccess. <br>Although we do this automaticly for you, you can still customize it you yourself.<br> <ul>    <li>Turn off the VueFront app. Switch the toggle on top of the page to OFF</li>    <li>Use your text editor to edit the .htaccess file with the following rules:</li>  </ul>",
  "buttonCopied": "copied",
  "buttonCopy": "copy",
  "textConfigureNginx": "Configure Nginx configuration file",
  "descriptionConfigureNginx": "For VueFront Web App to replace your current frontend, you will need to configure your Nginx configuration file. This can only be done manually via your FTP or File Manager. Use your text editor to add the following rules:"
}
</i18n>
<style lang="scss">
  .development.development.development .development {
    &__cms_connect {
      height: 54px !important;
      background-color: #efeff1!important;
      padding: 12px 24px!important;
      line-height: 30px!important;
      font-family: 'Open Sans', sans-serif !important;
      font-size: 24px !important;
      font-weight: 600;
      font-stretch: normal;
      font-style: normal;
      letter-spacing: 0.24px;
      text-align: left;
      color: $black !important;
      border: none!important;
      flex: 1;
      @media (--phone-and-tablet) {
        overflow: hidden;
      }
      &_wrapper {
        margin-bottom: 20px;
        width: 100%;
      }
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
      margin-bottom: 10px;
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
      @media (--phone-and-tablet) {
        padding: 20px 25px;
      }
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
        overflow-y: auto;
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
        @media(--phone-and-tablet) {
          font-size: 24px;
        }
      }
    }
  }
</style>

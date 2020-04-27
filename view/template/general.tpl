<vf-app class="vuefront-app"></vf-app>
<script>
document.addEventListener('DOMContentLoaded', function (event) {
  d_vuefront({
    selector: '.vuefront-app',
    baseURL: '<?php echo get_site_url(); ?>/',
    siteUrl: '<?php echo get_site_url(); ?>/',
    apiURL: '<?php echo admin_url('admin-ajax.php') ?>',
    type: 'wordpress'
  })
})
</script>

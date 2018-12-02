<?php
class Remember_Previous extends Plugin {

  private $host;


  function about() {
    return [
      1.3, // version
      'Remember your last-viewed category or feed.', // description
      'wn', // author
      false, // is system
      'https://www.github.com/supahgreg/ttrss-remember-previous', // more info URL
    ];
  }


  function api_version() {
    return 2;
  }


  function init($host) {
    //$this->host = $host;
  }


  function get_js() {
    return <<<'JS'
require(['dojo/ready'], (ready) => {
  ready(() => {
    const COOKIE_NAME = 'remember_previous';

    // Set our cookie when the active feed changes
    PluginHost.register(PluginHost.HOOK_FEED_SET_ACTIVE, ([aId,aIsCategory]) => {
      Cookie.set(COOKIE_NAME, aId + "," + (aIsCategory ? 1 : 0), 604800); // 1 week
    });

    PluginHost.register(PluginHost.HOOK_PARAMS_LOADED, () => {
      let prev = Cookie.get(COOKIE_NAME);
      if (/^-?\d+,[01]$/.test(prev)) {
        console.log(`[Remember_Previous] restoring category or feed: ${prev}`);
        prev = prev.split(',');
        hash_set('f', prev[0]);
        hash_set('c', prev[1]);
      }
    });
  });
});
JS;
  }
}
?>

<?php
class Remember_Previous extends Plugin {

  private $host;


  function about() {
    return Array(
        1.2 // version
      , "Remember your last-viewed category or feed." // description
      , "wn" // author
      , false // is system
      , "https://www.github.com/supahgreg/ttrss-remember-previous" // more info URL
    );
  }


  function api_version() {
    return 2;
  }


  function init($host) {
    //$this->host = $host;
  }
 

  function get_js() {
    return <<<'JS'
;(function(aSetActiveFeedId) {
  var oldSetActiveFeedId = aSetActiveFeedId
    , COOKIE_NAME = "remember_previous"
    , prev
    ;

  // Wrap the original setActiveFeedId so we can remember
  function _setActiveFeedId(aId, aIsCategory) {
    setCookie(COOKIE_NAME, aId + "," + (aIsCategory ? 1 : 0), 604800); // 1 week
    return oldSetActiveFeedId.call(null, aId, aIsCategory);
  }

  window.setActiveFeedId = _setActiveFeedId;

  prev = getCookie(COOKIE_NAME);
  if (!(prev && /^-?\d+,[01]$/.test(prev)))
    return;

  console.log("restoring category or feed: " + prev);
  prev = prev.split(",");
  hash_set("f", prev[0]);
  hash_set("c", prev[1]);
})(setActiveFeedId);
JS;
  }
}
?>

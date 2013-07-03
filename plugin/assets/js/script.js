jQuery(document).ready(function ($) {
  "use strict";

  window.dkowptools = {};
  var dkowptools = window.dkowptools;
  var $resolutionNode = $('.dkowptools_resolution');

  dkowptools.updateResolutionNode = function () {
    $resolutionNode.text($(window).width() + ' x ' + $(window).height());
  };

  if ($resolutionNode.length) {
    $(window).resize(dkowptools.updateResolutionNode);
    dkowptools.updateResolutionNode();
  }

});

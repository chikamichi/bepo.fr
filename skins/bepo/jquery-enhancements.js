/*
 ** MediaWiki 'bepo' jQuery hooks.
 ** Copyright Jean-Denis Vauguet for http://www.bepo.fr
 ** License: GPL (http://www.gnu.org/copyleft/gpl.html)
 */

/*
 ** Hooks definitions come first, then each function is
 ** called in the jQuery .ready() final call.
 */

/* todo:
 ** - color variables
 */

// ----------------------------------------------------------------------------
// containers min dimensions
// ensure that tabs in the header are displayed above the page column only
// and that the dock is on a single line. Also handles large pics
function bepo_ScaleDimensions() {
  tabs_width = 0;
  dock_width = 0;

  $("#header > ul").children().each(function() {
    //alert($(this).width());
    tabs_width += $(this).width();
  });
  tabs_width += $("#side").width();

  //alert("suite");
  $("ul#dock-left").children().add("#personnal-tools").children().each(function() {
    //alert($(this).width());
    dock_width += $(this).width();
  });

  //alert(tabs_width + ' ' + dock_width + ' : ' + Math.max(tabs_width, dock_width) + ' => ' + 1.2*Math.max(tabs_width, dock_width) );
  $("#container").css("min-width", Math.max(1.15*tabs_width, dock_width));

  // ensure the largest img fits within the content column 
  largest_img = 0;
  $("img").each(function() {
    if ($(this).width() > largest_img) {
      largest_img = $(this).width();
    }
  });

  s_width = $("#side").width();
  mc_width = $("#mainContent").width();

  if (largest_img >= mc_width) {
    $("#container").css("width", s_width + largest_img + 45);
  }
}

// ----------------------------------------------------------------------------
// style and animate links
function bepo_StyleLinks() {
  // visited links are handled as normal ones
  var normal_color = "#003399";
  var new_color    = "#86A3AF";
  var hover_color  = "#961F35";
  
  var $normal_links = $("#mainContent a:not(.editsection a, .touche a)");
  var $visited_links = $("#mainContent a:visited:not(.editsection a, .touche a)");
  var $new_links = $("#mainContent a.new:not(.editsection a, .touche a)");

  $normal_links.css("color", normal_color);
  $visited_links.css("color", normal_color);
  $new_links.css("color", new_color);

  // mouse events
  $normal_links.hover(function() {
    $(this).animate({color: hover_color}, 150);
  },
  function() {
    if ($(this).hasClass("new")) {
      $(this).animate({color: new_color}, 150);
    } else {
      $(this).animate({color: normal_color}, 150);
    }
  });

  // keyboard events
  // see CSS
}

// ----------------------------------------------------------------------------
// style and animate wiki tabs
function bepo_WikiTabs() {
  // first set the standard opacity
  var opa       = 0.6;
  var opa_selct = 0.8; // it's impossible to define a single, ever working value
                        // for the alpha value of the background change along the
                        // horizontal, but 0.8 seems like a rather good mean-value
  $("#header li").animate({opacity: opa}, 1);
  $("#header li.selected").animate({opacity: opa_selct}, 1);

  // then animate on over/out
  $("#header li a").hover(function() {
    if ($(this).parent().hasClass("selected")) {
      //$(this).parent().animate({color: "red", opacity: 1}, 150);
    } else {
      $(this).parent().animate({color: "#ffffff", opacity: 1}, 150);
    }
    //$(this).parent().animate({opacity: 1}, 150);
  }, function() {
    if ($(this).parent().hasClass("selected")) {
      //$(this).parent().animate({color: "#cccccc", opacity: opa_selct}, 150);
    } else {
      $(this).parent().animate({color: "#ffffff", opacity: opa}, 150);
    }
    //$(this).parent().animate({opacity: 0.8}, 150);
  });
}

// ----------------------------------------------------------------------------
// highlight current glossary entry
function bepo_HighlightGlossary() {
  var $title = $("#mainContent h1:first");

  if ($title.html() == "Glossaire") {
    var anchor = jQuery.url.attr("anchor");
    $("h2 > span.mw-headline").each(function() {
      if ($(this).attr("id") == anchor) {
        $(this).parent().animate({opacity: 1.0}, 1000).effect("highlight", {}, 3000);
        // the animate at full opacity renders as an highlight delay workaround
      }
    });
  }
}

// ----------------------------------------------------------------------------
// subheading box
// MUST be called before 'delete some specific titles' action!
function bepo_Subheading() {
  // if the model is preceded with blank lines (at least one), an empty p is lurking
  var $prev = $("#subheading").prev();
  if ($prev.is("p")) {
    $prev.remove();
  }

  // moving the breadcrumb under the page title
  var $main_title = $("#mainContent h1:first");

  if ($main_title) {
    $("#subheading").insertAfter("#mainContent h1:first");
  } else {
    // not really useful piece of code actually
    $("#subheading").prependTo("#mainContent");
    $("#breadcrumb").css("margin-top", "5px");
  }
}

// ----------------------------------------------------------------------------
// delete some specific titles
function deleteHiddenTitles(titles, $title) {
  jQuery.each(titles, function(i, t) {
    // sometimes an empty title results from {{DISPLAYTITLE:}}, hence 'empty' check
    if ($title.html() == t || $title.is(':empty')) {
      $title.css({"display": "none"});
      $("#catlinks-box").css("margin-top", "20px");
      // there may be a breadcrumb
      $("#breadcrumb").css("margin-top", "5px");
    } 
  });
}

function bepo_DeleteTitles() {
  var titles = new Array();
  var $current_title = $("#mainContent h1:first");

  jQuery.ajax({
    url: "Pages_sans_titre",
    dataType: 'html',
    cache: false,
    success: function(data) {
      titles = $(data).find("#mainContent > p").html().split("\n");
      deleteHiddenTitles(titles, $current_title);
    }
  });
}

// ----------------------------------------------------------------------------
// animate menu items
function bepo_Menu() {
  var $item = $("#side ul#nav li ul li > a");
  
  $item.hover(function() {
    //$(this).effect("highlight", "#ccc", 100);
    $(this).animate({backgroundColor: '#f9f9f9'}, 250);
  }, function() {
    $(this).animate({backgroundColor: '#ebebeb'}, 250);
  });

  // todo: animate section span?
}

// ----------------------------------------------------------------------------
// animate search area
function bepo_Search() {
  // caution: the /wiki/ part of the uri is specific to bepo.fr setup
  // you may need to change it so it reflects your conf while testing locally,
  // but it seems "wiki" is a general purpose alias though

  $("#search-area").hover(function() {
    $("#q-submit").attr("src", "/wiki/skins/bepo/search-active.png");
  }, function() {
    $("#q-submit").attr("src", "/wiki/skins/bepo/search-inactive.png");
  });
  
  $("#q").hover(function() {    
    $(this).animate({color:             "#808080"}, 250);
    $("#search-area").animate({
                     borderTopColor:    "#dedede", 
                     borderBottomColor: "#dedede", 
                     borderLeftColor:   "#dedede", 
                     borderRightColor:  "#dedede"}, 250); 
  }, function() {
    $("#q").animate({color:             "#cccccc"}, 250);
    $("#search-area").animate({
                     borderTopColor:    "#eeeeee", 
                     borderBottomColor: "#eeeeee", 
                     borderLeftColor:   "#eeeeee", 
                     borderRightColor:  "#eeeeee"}, 250);  
  });
}

// ----------------------------------------------------------------------------
// animate dock links
function bepo_Dock() {
  $("#dock ul li .bubbleInfo > a, #dock ul#personnal-tools li > a").hover(function() {
    $(this).animate({color: "#ffde00"}, 250);
  }, function() {
    if ($(this).hasClass("active")) {
      $(this).animate({color: "#ffde00"}, 250); // yeah that's the same color, but you never know
    } else {
      $(this).animate({color: "#aaaaaa"}, 250);
    }
  });
}

// ----------------------------------------------------------------------------
// style and animate editsection links
function bepo_EditSection() {
  $(".editsection a").css("color", "#b4b4b4");
  $(".editsection a").hover(function() {
    $(this).parent().css("background", "#424242");
    $(this).parent().css("color", "#424242");
    $(this).animate({color: "#ffffff"}, 250);
  }, function() {
    $(this).parent().css("background", "#e9e9e9");
    $(this).parent().css("color", "#e9e9e9");
    $(this).animate({color: "#b4b4b4"}, 250);
  });
}

// ----------------------------------------------------------------------------
// style and animate <pre>
function bepo_AnimatePre() {
  // destroy links
  $("pre a").each(function() {
    $(this).replaceWith($(this).html());
  });

  // animate
  $("pre:not(.de1, .de2, .li1, .li2, .source-css, .source-javascript)").hover(function() {
    active = true;
    $(this).prev().animate({borderLeftColor: "#891A91"}, 250); // prev() matches the associated .linenumbers pre
    $(this).animate({backgroundColor: "#ffffff"}, 250);
  }, function() {
    active = false;
    $(this).prev().animate({borderLeftColor: "#cccccc"}, 250);
    $(this).animate({backgroundColor: "#f2f2f2"}, 250);
  });
}      

function bepo_StylePre() {
  // line numbers
  $(".li1, .li2").css({
    "padding": "0",
    "margin": "0"
  });

  // code container
  $(".mw-geshi > div").css({
    "background": "#ffffff",
    "border-left": "2px solid #891A91",
    "padding-left": "0.5em"
  });
  
  // code
  $(".mw-geshi pre").css({
    "border": "none",
    "background": "transparent",
    "margin": "0",
    "padding": "0.5em 0.3em"
  });

  $(".mw-geshi > div > ol").css({
    "padding-top": "0.5em",
    "padding-bottom": "0.5em"
  });

  $(".mw-geshi > div > ol li pre").css({
    "padding": "0 0.3em"
  });
}

// ----------------------------------------------------------------------------
// style and animate link-to-top
function bepo_LinkToTop() {
  if ($("#mainContent").height() < 500) {
    return;
  } else {
    var opa = 0.6;

    $("#link-to-top").css("display", "block");
    $("#link-to-top").animate({opacity: opa});
    $("#link-to-top").hover(function() {
      $(this).animate({opacity: 1}, 250);
    }, function() {
      $(this).animate({opacity: opa}, 250);
    });
  }
}      

// ----------------------------------------------------------------------------
// style and animate categories
function bepo_Categories() {
  var opa = 0.6;

  $("#catlinks-box").animate({opacity: opa});
  //$("#catlinks-box").mouseover(function() {
    //$(this).animate({opacity: 1}, 250);
  //}).mouseout(function() {
    //$(this).animate({opacity: opa}, 250);
  //});
}      

// ----------------------------------------------------------------------------
// animate images in thumbs
function bepo_Images() {
  $(".thumbinner .image").hover(function() {
    $(this).parent().parent().animate({backgroundColor: "#ffffff"}, 250);
  }, function() {
    $(this).parent().parent().animate({backgroundColor: "#f9f9f9"}, 250);
  });
}      

// ----------------------------------------------------------------------------
// tooltips -- using the qTip library
function bepo_DockTooltips() {
  // get rid of some inherited style
  $(".tooltipContent h2").css({border: "none", "font-size": '1.1em', margin: "0 1em 0.5em 0", padding: 0});
  $(".tooltipContent a").css({"float": "none", "display": "inline", "color": "#003399"});
  $(".tooltipContent a").hover(function() {
    $(this).animate({color: "#961F35"}, 250);
  }, function() {
    $(this).animate({color: "#003399"}, 250);
  });
  $(".tooltipContent ul").css({margin: 0, padding: "0 0 0 1.5em"});

  // content -- is defined as inner .tooltipContent div's
  
  // close tooltips on some specific actions
  $("#mainContent").mouseover(function() {
    $("ul#dock-left a.trigger").qtip("hide");
  });

  // style
  $('ul#dock-left a.trigger').removeAttr("title").each(function(i) {
    $(this).qtip({
      //content: $contents[i],
      //prerender: true,
      content: $(this).next('.tooltipContent:first'),
      show: 'mouseover',
      hide: 'mouseout',
      position: {
        corner: {
          target: 'rightBottom',
          tooltip: 'topLeft'
        }
      },
      style: {
        tip: {
          corner: 'topLeft',
          color: '#f9f9f9'
        },
        border: {
                   width: 0,
                   radius: 2,
                   color: '#f9f9f9'
                },
        //border: 'none',
        background: '#f9f9f9',
        color: '#000000',
        'text-align': 'justify',
        'line-height': '1.5em',
        width: 'auto',
        'max-width': 0.8 * $("#main").width(),
        'z-index': 1100
      },
      show: {
        //effect: function(length){ $(this).animate({"top": "+=10px", opacity: "toggle"}, 200); }
        //effect: function(length){ $(this).show("drop", 150); }
                // do not use "show" as opacity value, for it may save non boolean value if
                // the tooltip is hidden while fading in/out, resulting in a transparent tooltip
        effect: { type: 'fade',
                  length: 200
                }
      },
      hide: {
        effect: { type: 'fade',
                  length: 200
                },
        delay: 3000,
        when: { event: 'inactive' }
      },
      api: {
        beforeShow: function(){
          $("ul#dock-left a.trigger").qtip("hide");
        }
      }
    });
  });
}

// display keyboard tooltips
function bepo_KeyboardTooltips() {
  $(".touche a").removeAttr("title");
  
  $(".touche").not("a").hover(function() {
    $(this).find(".keyboard-tooltip").animate({opacity: 0}, 1)
                                     .css("display", "inline")
                                     .animate({opacity: 1}, 150);
  },function() {
    $(this).find(".keyboard-tooltip").css("display", "none");
  });
}

// ----------------------------------------------------------------------------
// style and animate footer icons
function bepo_Footer() {
  // first set the standard opacity
  var opa       = 0.3;

  $("#footer-icons > span > a > img").animate({opacity: opa}, 1);

  // then animate on over/out
  $("#footer-icons > span > a > img").hover(function() {
    $(this).animate({opacity: 1}, 150);
  }, function() {
    $(this).animate({opacity: opa}, 150);
  });
}

// ----------------------------------------------------------------------------
// style and animate installation tabs
// this is based on jQuery native tabs implementation and some MediaWiki
// subpages holding the content. See the wiki discussion page for details on
// how to edit and extend.
function bepo_InstallationTabsStyle() {
  //$("#installation > ul.ui-tabs-nav > li.ui-tabs-selected").hover(function() {
      //$(this).animate({backgroundColor: "#961F35"}, 1);
    //}, function() {
      //$(this).animate({backgroundColor: "#961F35"}, 1);
    //}
  //);

  //$("#installation > ul.ui-tabs-nav > li").not(".ui-tabs-selected").hover(function() {
      //$(this).animate({backgroundColor: "red"}, 150);
    //}, function() {
      //$(this).animate({backgroundColor: "#ebebeb"}, 150);
    //}
  //);

  $("#installation > ul.ui-tabs-nav > li").not(".ui-tabs-selected").find("a").css("color", "#000000");
  $("#installation > ul.ui-tabs-nav > li.ui-tabs-selected > a").css("color", "#ffffff");
  $("#installation > ul.ui-tabs-nav > li.ui-tabs-selected").css("background", "#961F35");
  $("#installation > ul.ui-tabs-nav > li").not(".ui-tabs-selected").css("background", "#e9e9e9");
}

function bepo_InstallationTabs() {
  // do this only on the installation page
  var name    = "Installation";
  
  if ($("body").hasClass("page-" + name)) {
    var systems = [
                   "Windows",
                   "Unix",
                   "Macintosh"
                  ];
    var contents = new Array();

    // removing bottomContent for the sake of clear rules
    var $bottomContent = $("#bottomContent");
    $("#bottomContent").remove();

    // let's create the tabs
    
    // create the html structure
    var insert_this =
      '<div id="installation">\n' +
      '<ul>\n';

    // structure: here are the very tabs
    for (var i = 0; i < systems.length; i++) {
      var sys = systems[i];
      insert_this += '<li><a href="#tabs-' + i + '" class="' + sys.toLowerCase() + '"><span>' + sys + '</span></a></li>\n';
    }

    insert_this += '</ul>\n';

    // structure: and the installation descriptions (tabs contents)
    for (i = 0; i < systems.length; i++) {
      insert_this += '<div class="installation-desc" id="tabs-' + i + '"></div>\n';
    }

    insert_this += '</div>'; // #installation
    //insert_this += '<hr class="clr invisible" />';

    // structure: insert the stuff
    $("#mainContent").append(insert_this);

    // let's then load the very content
    var $desc = new String;
    jQuery.each(systems, function(j, sys) {
      jQuery.ajax({
        url: name + "/" + systems[j],
        dataType: 'html',
        cache: false,
        success: function(data) {
          $desc = $(data).find("#mainContent");
          $desc.find("h1, h3, #contentSub, #bottomContent, .printfooter").remove();
          //alert($desc);
          $('#tabs-' + j).html($desc.html());
        }
      });
    });

    // plug it all
    $("#installation").tabs();
                      //.bind('tabsselect', function(event, ui) {
                        //$("#installation > ul.ui-tabs-nav > li.ui-tabs-selected").css("background", "#961F35");
                      //});

    // remap some lurking chunks
    
    // get the header data
    var $entete = new String;
    jQuery.ajax({
      url: name + "/En-tête",
      dataType: 'html',
      cache: false,
      success: function(data) {
        $entete = $(data).find("#mainContent");
        $entete.find("h1, h3, #contentSub, #bottomContent, .printfooter").remove();
        $("#mainContent").prepend($entete.html());
      }
    });

    $bottomContent.appendTo("#mainContent");

    // a couple of effects

    bepo_InstallationTabsStyle();
    $("#installation").bind('tabsshow', function(event, ui) {
      bepo_InstallationTabsStyle();
    });
  }
}

// ----------------------------------------------------------------------------
// folding divs
function init_folding_divs() {
  $(".folding-content").each(function() {
    if ($(this).hasClass("non")) {
      $(this).hide();
      $(this).prev().find(".folding-notice").html("afficher");
      $(this).prev().css("background", '#e9e9e9 url("/wiki/skins/bepo/folding-arrow-unfold.png") center right no-repeat');
    } else {
      $(this).prev().css("background", '#e9e9e9 url("/wiki/skins/bepo/folding-arrow-fold.png") center right no-repeat');
    }
  });
}

function style_folding_divs() {
  $(".folding-trigger").hover(function() {
    $(this).css("background-color", "#d9d9d9");
  }, function() {
    $(this).css("background-color", "#e9e9e9");
  });
}

function toggle_folding(el) {
  el.toggleClass("oui");
  el.toggleClass("non");

  var bg = el.prev().css("background-image");
  var suffix = /.*(-unfold.png\))$/;
  if (suffix.test(bg)) {
    el.prev().css("background-image", "url(/wiki/skins/bepo/folding-arrow-fold.png)");
  } else {
    el.prev().css("background-image", "url(/wiki/skins/bepo/folding-arrow-unfold.png)");
  }
}

// ugly o:D DRY anyone?
function bepo_Folding() {
  init_folding_divs();
  style_folding_divs();

  var $trigger = $(".folding-trigger");
  var hidden = !$trigger.next().hasClass("oui");  // matching "afficher" class on content

  if (hidden) {
    $trigger.toggle(function() {
      $(this).next().show(150);
      $(this).find(".folding-notice").html("masquer");
      toggle_folding($(this).next());
    }, function() {
      $(this).next().hide(150);
      $(this).find(".folding-notice").html("afficher");
      toggle_folding($(this).next());
    })
  } else {
    $trigger.toggle(function() {
      $(this).next().hide(150);
      $(this).find(".folding-notice").html("afficher");
      toggle_folding($(this).next());
    }, function() {
      $(this).next().show(150);
      $(this).find(".folding-notice").html("masquer");
      toggle_folding($(this).next());
    })
  }
}

// ----------------------------------------------------------------------------
// main call
$(document).ready(function() {
  bepo_ScaleDimensions();
  bepo_StyleLinks();
  bepo_WikiTabs();
  bepo_HighlightGlossary();
  bepo_Subheading();
  bepo_DeleteTitles();
  bepo_Menu();
  bepo_Search();
  bepo_Dock();
  bepo_EditSection();
  bepo_AnimatePre();
  bepo_StylePre();
  bepo_LinkToTop();
  bepo_Categories();
  bepo_Images();
  bepo_DockTooltips();
  bepo_KeyboardTooltips();
  bepo_Footer();
  //bepo_InstallationTabs();
  bepo_Folding();
});


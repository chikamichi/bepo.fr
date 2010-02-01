<?php
/*
 ** MediaWiki 'bepo' skin.
 ** Copyright Jean-Denis Vauguet for http://www.bepo.fr
 ** License: GPL (http://www.gnu.org/copyleft/gpl.html)
 **
 ** Based on the Cavendish style by Mozilla Foundation.
 ** Sync with MonoBook "nouveau" 18/09/09.
 **
 ** Several styles are altered by jQuery hooks. Update
 ** jquery-enhancements.js accordingly.
 */

if( !defined( 'MEDIAWIKI' ) )
  die( -1 );

/** */
require_once('includes/SkinTemplate.php');

/**
 * Inherit main code from SkinTemplate, set the CSS and template filter.
 * @todo document
 * @ingroup Skins
 */
class SkinBepo extends SkinTemplate {
  /** Using bepo. */
  var $skinname = 'bepo', $stylename = 'bepo',
    $template = 'BepoTemplate';

  function setupSkinUserCss( OutputPage $out ) {
    global $wgHandheldStyle;

    parent::setupSkinUserCss( $out );

    // Append to the default screen common & print styles...
    $out->addStyle( 'bepo/main.css', 'screen' );
    
    if( $wgHandheldStyle ) {
      // Currently in testing... try 'chick/main.css'
      $out->addStyle( $wgHandheldStyle, 'handheld' );
    }

    $out->addStyle( 'bepo/IE60Fixes.css', 'screen', 'IE 6' );
    $out->addStyle( 'bepo/IE70Fixes.css', 'screen', 'IE 7' );
    $out->addStyle( 'bepo/IE80Fixes.css', 'screen', 'IE 8' );

    //$out->addStyle( 'monobook/rtl.css', 'screen', '', 'rtl' );

    //@@todo we can move this to the parent once we update all skins
    if( isset( $this->pagecss ) &&  $this->pagecss)
      $out->addInlineStyle( $this->pagecss );

    if( isset( $this->usercss ) &&  $this->usercss)
      $out->addInlineStyle( $this->usercss );

  }
  function setupSkinUserJs( OutputPage $out ) {
    parent::setupSkinUserJs( $out );
    $out->addScriptFile( 'wikibits.js' );

    //@@todo can move to parent once we update all skins (to not include things twice
    if( isset( $this->jsvarurl ) && $this->jsvarurl)
      $out->addScriptFile( $this->jsvarurl );

    if( isset( $this->userjs ) && $this->userjs)
      $out->addScriptFile( $this->userjs );

    if( isset( $this->userjsprev ) && $this->userjsprev)
      $out->addInlineScript( $this->userjsprev );
  }
}

/**
 * @todo document
 * @ingroup Skins
 */
class BepoTemplate extends QuickTemplate {
  var $skin;
  /**
   * Template filter callback for Bepo skin.
   * Takes an associative array of data set from a SkinTemplate-based
   * class, and a wrapper for MediaWiki's localization database, and
   * outputs a formatted page.
   *
   * @access private
   */
  function execute() {
    global $wgRequest, $wgOut, $wgStyleVersion, $wgJsMimeType, $wgStylePath, $wgScriptPath;
    $this->skin = $skin = $this->data['skin'];
    $action = $wgRequest->getText( 'action' );

    // Suppress warnings to prevent notices about missing indexes in $this->data
    wfSuppressWarnings();
    # FIXME: What is this?  Should it apply to all skins?
    $path      = htmlspecialchars( $wgStylePath );
    $base_path = htmlspecialchars( $wgScriptPath );

    //$wgOut->addScript('<script src="' . $base_path . '/extensions/jquery/jquery-1.3.2.min.js" type="text/javascript"></script>');
    //$wgOut->addScript('<script src="' . $base_path . '/extensions/jquery-ui/js/jquery-ui-1.7.2.custom.min.js" type="text/javascript"></script>');
    
    //$wgOut->addScript('<script src="' . $base_path . '/extensions/jquery-qtip/jquery.qtip-1.0.0-rc3.min.js" type="text/javascript"></script>');

    //$wgOut->addScript('<script src="' . $base_path . '/extensions/excanvas/excanvas.js" type="text/javascript"></script>');
    //$wgOut->addScript('<script src="' . $path .      '/bepo/jquery-enhancements.js" type="text/javascript"></script>');

    echo $wgOut->headElement( $this->skin );

?><body<?php if($this->data['body_ondblclick']) { ?> ondblclick="<?php $this->text('body_ondblclick') ?>"<?php } ?>
<?php if($this->data['body_onload']) { ?> onload="<?php $this->text('body_onload') ?>"<?php } ?>
 class="mediawiki <?php $this->text('dir'); $this->text('capitalizeallnouns') ?> <?php $this->text('pageclass') ?> <?php $this->text('skinnameclass') ?>">
<div id="container">
  <!-- the dock is used as a placeholder for extra site navigation and intra site personnal tools links -->
  <div id="dock">
    <div class="left"></div> <!-- left border -->
    <!-- dock-left contains several tooltips handled by qTip -->
    <ul id="dock-left">
      <li id="trigger-wiki" class="first">
        <a href="http://www.bepo.fr" class="trigger active" title="Wiki" name="top">wiki</a>
        <div class="tooltipContent">
          <h2>le site web</h2>
          ieiueiueguides d'installation, documentation, conseil d'apprentissage…
        </div>
      </li>
      <li id="trigger-forum">
        <a href="http://forum.bepo.fr" class="trigger" title="Forum">forum</a>
        <div class="tooltipContent">
          <h2>forum de discussion</h2>
          le lieu principal d'échange – notez qu'il existe également une <a href="Communauté">liste de diffusion</a> !
        </div>
      </li>
      <li id="trigger-svn">
        <a href="http://svnweb.tuxfamily.org/listing.php?repname=dvorak/svn&path=%2F&sc=0" class="trigger" title="Dépôt subversion (téléchargement)">svn</a>
        <div class="tooltipContent">
          <h2>dépôt subversion</h2>
          vous pourrez y télécharger l'ensemble des programmes et données du projet
        </div>
      </li>
    </ul>
    <!-- MediaWiki user tools -->
    <ul id="personnal-tools">
    <?php foreach($this->data['personal_urls'] as $key => $item) {
    ?><li id="pt-<?php echo htmlspecialchars($key) ?>"><a href="<?php
    echo htmlspecialchars($item['href']) ?>"<?php
    if(!empty($item['class'])) { ?> class="<?php
      echo htmlspecialchars($item['class']) ?>"<?php } ?>><?php
      echo htmlspecialchars($item['text']) ?></a></li><?php } ?>
    </ul>
    <div class="right"></div> <!-- right border -->
  </div>

  <?php if($this->data['sitenotice']) { ?><div id="siteNotice"><?php $this->html('sitenotice') ?></div><?php } ?>

  <div id="main">
    <?php
    // let's pick up a header background among those available and choose the appropriate logo version accordingly
    $rand_bg         = rand(1,2);
    $bg_white_values = array(1); // headers for which a white logo must be used 
    in_array($rand_bg, $bg_white_values) ? $logo_color = "white" : $logo_color = "black";
    ?>
    <div id="header" style="background: #0D0D0E url('<?php $this->text('stylepath') ?>/bepo/header_bg<?php echo $rand_bg ?>.png') bottom right no-repeat;">
      <div id="bepo-logo" style="background: url('<?php $this->text('stylepath') ?>/bepo/logo_bepo_<?php echo $logo_color ?>.png') top left no-repeat;"><a href="http://www.bepo.fr"><span></span></a></div> 
      <a name="top" id="contentTop"></a>
      <!--<h1><a
        href="<?php echo htmlspecialchars($this->data['nav_urls']['mainpage']['href'])?>"
        title="<?php $this->msg('mainpage') ?>"><?php $this->text('title') ?></a></h1>-->

      <ul>
      <?php foreach($this->data['content_actions'] as $key => $action) {
      ?><li
        <?php if($action['class']) { ?>class="<?php echo htmlspecialchars($action['class']) ?>"<?php } ?>
        ><a href="<?php echo htmlspecialchars($action['href']) ?>"><?php
        echo htmlspecialchars($action['text']) ?></a></li><?php } ?>
      </ul>
    </div>

    <div id="mBody" style="background: #f9f9f9 url('<?php $this->text('stylepath' ) ?>/bepo/maincontent_bg<?php echo $rand_bg ?>.png') top right no-repeat;">
      <div id="side">
        <ul id="nav">
          <li id="nav-search">
            <form name="searchform" action="<?php $this->text('searchaction') ?>" id="search">
              <div id="search-area">
                <input id="q" name="search" type="text"
                <?php if($this->haveMsg('accesskey-search')) { ?>accesskey="<?php $this->msg('accesskey-search') ?>"<?php } ?>
                value="rechercher…" onClick="if(this.value=='rechercher…')this.value=''" onfocus="if (this.value == 'rechercher…') { this.value = ''; }" onblur="if (this.value == '') { this.value = 'rechercher…'; }" />
                <input id="q-submit" type="image" src="<?php $this->text('stylepath') ?>/bepo/search-inactive.png" name="q-submit" alt="Rechercher" />
              </div>
            </form>
          </li>
          <?php foreach ($this->data['sidebar'] as $bar => $cont) { ?>
          <li><span><?php $out = wfMsg( $bar ); if (wfEmptyMsg($bar, $out)) echo $bar; else echo $out; ?></span>
            <ul>
              <?php foreach($cont as $key => $val) { ?>
              <li id="<?php echo htmlspecialchars($val['id']) ?>"<?php if ( $val['active'] ) { ?> class="active" <?php } ?>>
              <a href="<?php echo htmlspecialchars($val['href']) ?>"><?php echo htmlspecialchars($val['text']) ?></a>
              </li>
              <?php } ?>
            </ul>
          </li>
          <?php } ?>
          <li><span><?php $this->msg('toolbox') ?></span>
            <ul>
              <?php if($this->data['notspecialpage']) { foreach( array( 'whatlinkshere', 'recentchangeslinked' ) as $special ) { ?>
              <li id="t-<?php echo $special?>"><a href="<?php echo htmlspecialchars($this->data['nav_urls'][$special]['href']) ?>"><?php echo $this->msg($special) ?></a></li>
              <?php } } ?>
              <?php if($this->data['feeds']) { ?><li id="feedlinks"><?php foreach($this->data['feeds'] as $key => $feed) { ?>
              <span id="feed-<?php echo htmlspecialchars($key) ?>"><a href="<?php echo htmlspecialchars($feed['href']) ?>"><?php echo htmlspecialchars($feed['text'])?></a>&nbsp;</span>
              <?php } ?>
              </li>
              <?php } ?>
              <?php foreach( array('contributions', 'emailuser', 'upload', 'specialpages') as $special ) { ?>
              <?php if($this->data['nav_urls'][$special]) {?><li id="t-<?php echo $special ?>">
              <a href="<?php echo htmlspecialchars($this->data['nav_urls'][$special]['href']) ?>"><?php $this->msg($special) ?></a>
              </li>
              <?php } ?>
            <?php } ?>
            </ul>
          </li>
          <?php if( $this->data['language_urls'] ) { ?>
          <li><span><?php $this->msg('otherlanguages') ?></span>
            <ul>
              <?php foreach($this->data['language_urls'] as $langlink) { ?>
              <li><a href="<?php echo htmlspecialchars($langlink['href']) ?>"><?php echo $langlink['text'] ?></a></li>
              <?php } ?>
            </ul>
          </li>
          <?php } ?>
        </ul>
      </div><!-- end of SIDE div -->

      <div id="mainContent">
        <h1><?php $this->text('title') ?></h1>
        <h3 id="siteSub"><?php $this->msg('tagline') ?></h3>
        <div id="contentSub">
          <?php $this->html('subtitle') ?>
        </div>
        <?php if($this->data['undelete']) { ?><div id="contentSub"><?php     $this->html('undelete') ?></div><?php } ?>
        <?php if($this->data['newtalk'] ) { ?><div class="usermessage"><?php $this->html('newtalk')  ?></div><?php } ?>

        <!-- start content -->
        <?php $this->html('bodytext') ?>
        <!-- end content -->

        <div id="bottomContent">
          <div id="link-to-top-box"><a href="#top" id="link-to-top">retour en haut</a></div>
          <?php if($this->data['catlinks']) { ?><div id="catlinks-box"><?php $this->html('catlinks') ?></div><?php } ?>
          <hr class="clr invisible" />
        </div>
      </div><!-- end of the MAINCONTENT div -->

    </div><!-- end of the MBODY div -->

  </div><!-- end of MAIN div -->

    <div id="footer">
      <div id="footer-icons">
        <span id="footer-bepo"><a href="http://www.bepo.fr"><img src="<?php echo $path . '/bepo/bepo-powered.png' ?>" alt="logo bepo.fr" title="bepo.fr" /></a></span>
        <span id="footer-licence"><a href="<?php echo $base_path ?>/À propos"><img src="<?php echo $path . '/bepo/cc-by-sa-gfdl.png' ?>" alt="logo des licences" title="Contenu sous double licence CC-BY-SA et GFDL" /></a></span>
        <span id="footer-mw"><a href="http://www.mediawiki.org"><img src="<?php echo $path . '/bepo/thx-mediawiki.png' ?>" alt="logo mediawiki.org" title="mediawiki.org" /></a></span>
      </div>
      <div id="footer-text">
        <?php if($this->data['lastmod'   ]) { ?><span id="f-lastmod"><?php    $this->html('lastmod')    ?></span><?php } ?>
        <?php if($this->data['viewcount' ]) { ?><span id="f-viewcount"><?php  $this->html('viewcount')  ?></span><?php } ?>
      </div>
      <div id="footer-clear"></div>
    </div><!-- end of the FOOTER div -->

</div><!-- end of the CONTAINER div -->

<?php $this->html('reporttime') ?>
<script src="<?php echo $base_path ?>/extensions/jquery/jquery-1.3.2.min.js" type="text/javascript"></script>
<script src="<?php echo $base_path ?>/extensions/jquery-ui/js/jquery-ui-1.7.2.custom.min.js" type="text/javascript"></script>
<script src="<?php echo $base_path ?>/extensions/jquery-url-parser/jquery.url.packed.js" type="text/javascript"></script>
<script src="<?php echo $base_path ?>/extensions/jquery-qtip/jquery.qtip-1.0.0-rc3.min.js" type="text/javascript"></script>
<script src="<?php echo $path ?>/bepo/jquery-enhancements.js" type="text/javascript"></script>
</body></html>
<?php
  wfRestoreWarnings();
  } // end of execute() method

  /*************************************************************************************************/
  function searchBox() {
    global $wgUseTwoButtonsSearchForm;
?>
  <div id="p-search" class="portlet">
    <h5 <?php $this->html('userlangattributes') ?>><label for="searchInput"><?php $this->msg('search') ?></label></h5>
    <div id="searchBody" class="pBody">
      <form action="<?php $this->text('wgScript') ?>" id="searchform">
        <input type='hidden' name="title" value="<?php $this->text('searchtitle') ?>"/>
        <input id="searchInput" name="search" type="text"<?php echo $this->skin->tooltipAndAccesskey('search');
          if( isset( $this->data['search'] ) &&  $this->data['search'] ) {
            ?> value="<?php $this->text('search') ?>"<?php } ?> />
        <input type='submit' name="go" class="searchButton" id="searchGoButton" value="<?php $this->msg('searcharticle') ?>"<?php echo $this->skin->tooltipAndAccesskey( 'search-go' ); ?> /><?php if ($wgUseTwoButtonsSearchForm) { ?>&nbsp;
        <input type='submit' name="fulltext" class="searchButton" id="mw-searchButton" value="<?php $this->msg('searchbutton') ?>"<?php echo $this->skin->tooltipAndAccesskey( 'search-fulltext' ); ?> /><?php } else { ?>

        <div><a href="<?php $this->text('searchaction') ?>" rel="search"><?php $this->msg('powersearch-legend') ?></a></div><?php } ?>

      </form>
    </div>
  </div>
<?php
  }

  /*************************************************************************************************/
  function toolbox() {
?>
  <div class="portlet" id="p-tb">
    <h5 <?php $this->html('userlangattributes')?>><?php $this->msg('toolbox') ?></h5>
    <div class="pBody">
      <ul>
<?php
    if($this->data['notspecialpage']) { ?>
        <li id="t-whatlinkshere"><a href="<?php
        echo htmlspecialchars($this->data['nav_urls']['whatlinkshere']['href'])
        ?>"<?php echo $this->skin->tooltipAndAccesskey('t-whatlinkshere') ?>><?php $this->msg('whatlinkshere') ?></a></li>
<?php
      if( $this->data['nav_urls']['recentchangeslinked'] ) { ?>
        <li id="t-recentchangeslinked"><a href="<?php
        echo htmlspecialchars($this->data['nav_urls']['recentchangeslinked']['href'])
        ?>"<?php echo $this->skin->tooltipAndAccesskey('t-recentchangeslinked') ?>><?php $this->msg('recentchangeslinked-toolbox') ?></a></li>
<?php     }
    }
    if( isset( $this->data['nav_urls']['trackbacklink'] ) && $this->data['nav_urls']['trackbacklink'] ) { ?>
      <li id="t-trackbacklink"><a href="<?php
        echo htmlspecialchars($this->data['nav_urls']['trackbacklink']['href'])
        ?>"<?php echo $this->skin->tooltipAndAccesskey('t-trackbacklink') ?>><?php $this->msg('trackbacklink') ?></a></li>
<?php   }
    if($this->data['feeds']) { ?>
      <li id="feedlinks"><?php foreach($this->data['feeds'] as $key => $feed) {
          ?><a id="<?php echo Sanitizer::escapeId( "feed-$key" ) ?>" href="<?php
          echo htmlspecialchars($feed['href']) ?>" rel="alternate" type="application/<?php echo $key ?>+xml" class="feedlink"<?php echo $this->skin->tooltipAndAccesskey('feed-'.$key) ?>><?php echo htmlspecialchars($feed['text'])?></a>&nbsp;
          <?php } ?></li><?php
    }

    foreach( array('contributions', 'log', 'blockip', 'emailuser', 'upload', 'specialpages') as $special ) {

      if($this->data['nav_urls'][$special]) {
        ?><li id="t-<?php echo $special ?>"><a href="<?php echo htmlspecialchars($this->data['nav_urls'][$special]['href'])
        ?>"<?php echo $this->skin->tooltipAndAccesskey('t-'.$special) ?>><?php $this->msg($special) ?></a></li>
<?php   }
    }

    if(!empty($this->data['nav_urls']['print']['href'])) { ?>
        <li id="t-print"><a href="<?php echo htmlspecialchars($this->data['nav_urls']['print']['href'])
        ?>" rel="alternate"<?php echo $this->skin->tooltipAndAccesskey('t-print') ?>><?php $this->msg('printableversion') ?></a></li><?php
    }

    if(!empty($this->data['nav_urls']['permalink']['href'])) { ?>
        <li id="t-permalink"><a href="<?php echo htmlspecialchars($this->data['nav_urls']['permalink']['href'])
        ?>"<?php echo $this->skin->tooltipAndAccesskey('t-permalink') ?>><?php $this->msg('permalink') ?></a></li><?php
    } elseif ($this->data['nav_urls']['permalink']['href'] === '') { ?>
        <li id="t-ispermalink"<?php echo $this->skin->tooltip('t-ispermalink') ?>><?php $this->msg('permalink') ?></li><?php
    }

    wfRunHooks( 'BepoTemplateToolboxEnd', array( &$this ) );
    wfRunHooks( 'SkinTemplateToolboxEnd', array( &$this ) );
?>
      </ul>
    </div>
  </div>
<?php
  }

  /*************************************************************************************************/
  function languageBox() {
    if( $this->data['language_urls'] ) {
?>
  <div id="p-lang" class="portlet">
    <h5 <?php $this->html('userlangattributes') ?>><?php $this->msg('otherlanguages') ?></h5>
    <div class="pBody">
      <ul>
<?php   foreach($this->data['language_urls'] as $langlink) { ?>
        <li class="<?php echo htmlspecialchars($langlink['class'])?>"><?php
        ?><a href="<?php echo htmlspecialchars($langlink['href']) ?>"><?php echo $langlink['text'] ?></a></li>
<?php   } ?>
      </ul>
    </div>
  </div>
<?php
    }
  }

  /*************************************************************************************************/
  function customBox( $bar, $cont ) {
?>
  <div class='generated-sidebar portlet' id='<?php echo Sanitizer::escapeId( "p-$bar" ) ?>'<?php echo $this->skin->tooltip('p-'.$bar) ?>>
    <h5 <?php $this->html('userlangattributes') ?>><?php $out = wfMsg( $bar ); if (wfEmptyMsg($bar, $out)) echo htmlspecialchars($bar); else echo htmlspecialchars($out); ?></h5>
    <div class='pBody'>
<?php   if ( is_array( $cont ) ) { ?>
      <ul>
<?php       foreach($cont as $key => $val) { ?>
        <li id="<?php echo Sanitizer::escapeId($val['id']) ?>"<?php
          if ( $val['active'] ) { ?> class="active" <?php }
        ?>><a href="<?php echo htmlspecialchars($val['href']) ?>"<?php echo $this->skin->tooltipAndAccesskey($val['id']) ?>><?php echo htmlspecialchars($val['text']) ?></a></li>
<?php     } ?>
      </ul>
<?php   } else {
      # allow raw HTML block to be defined by extensions
      print $cont;
    }
?>
    </div>
  </div>
<?php
  }
} // end of class


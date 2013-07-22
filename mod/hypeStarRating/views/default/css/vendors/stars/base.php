/*!
 * jQuery UI Stars v3.0.1
 * http://plugins.jquery.com/project/Star_Rating_widget
 *
 * Copyright (c) 2010 Marek "Orkan" Zajac (orkans@gmail.com)
 * Dual licensed under the MIT and GPL licenses.
 * http://docs.jquery.com/License
 *
 * $Rev: 164 $
 * $Date:: 2010-05-01 #$
 * $Build: 35 (2010-05-01)
 *
 */
.ui-stars-star,
.ui-stars-cancel {
  float: left;
  display: block;
  overflow: hidden;
  text-indent: -999em;
  cursor: pointer;
}
.ui-stars-star a,
.ui-stars-cancel a {
  width: 16px;
  height: 15px;
  display: block;
  background: url(<?php echo elgg_get_config('url') ?>mod/hypeStarRating/graphics/stars.gif) no-repeat 0 0;
}
.ui-stars-star a {
  background-position: 0 -32px;
}
.ui-stars-star-on a {
  background-position: 0 -48px;
}
.ui-stars-star-hover a {
  background-position: 0 -64px;
}
.ui-stars-cancel-hover a {
  background-position: 0 -16px;
}
.ui-stars-star-disabled,
.ui-stars-star-disabled a,
.ui-stars-cancel-disabled a {
  cursor: default !important;
}


/**
*   Elgg CSS Hacks
**/

form.hj-ajaxed-starrating {
background:none;
padding:0;
border:0;
height:auto;
}

.hj-starrating-caption {
text-align:center;
font-size:10px;
}

form.hj-ajaxed-starrating select {
height:auto;
width:auto;
}

.elgg-menu-entity, elgg-menu-annotation {
height:30px;
}
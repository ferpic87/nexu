<?php
/**
 * Mentions input CSS.
 *
 * Used as a view because we need to pass a full URL to AlphaImageLoader.
 *
 */

$jquery_path = elgg_get_site_url() . 'vendors/jquery/';
?>

.mentions-input-box {
  position: relative;
  background: #fff;
}

.mentions-input-box textarea{
  height: 18px;
  padding: 9px;
  border: 1px solid #dcdcdc;
  border-radius:3px;
  width: 90%;
}

.mentions-input-box textarea, .mentions-input-box input {
  display: block;
  -webkit-box-sizing: border-box;
     -moz-box-sizing: border-box;
          box-sizing: border-box;
  resize: none;
  overflow: hidden;
  background: transparent;
  outline: 0;
  position: relative;
}


.mentions-input-box .mentions-autocomplete-list {
  display: none;
  width:90%;
  background: #fff;
  border: 1px solid #b2b2b2;
  left: 0;
  right: 0;
  z-index: 10000;
  margin-top: -2px;
  border-radius:5px;
  border-top-right-radius:0;
  border-top-left-radius:0;

  -webkit-box-shadow: 0 2px 5px rgba(0, 0, 0, 0.148438);
     -moz-box-shadow: 0 2px 5px rgba(0, 0, 0, 0.148438);
          box-shadow: 0 2px 5px rgba(0, 0, 0, 0.148438);
}

.mentions-input-box .mentions-autocomplete-list ul {
    margin: 0;
    padding: 0;
}

.mentions-autocomplete-list {
  position: relative;
}

.mentions-input-box .mentions-autocomplete-list li {
  background-color: #fff;
  padding: 0 5px;
  margin: 0;
  width: auto;
  border-bottom: 1px solid #eee;
  height: 26px;
  line-height: 26px;
  overflow: hidden;
  cursor: pointer;
  list-style: none;
  white-space: nowrap;
}

.mentions-input-box .mentions-autocomplete-list li:last-child {
  border-radius:5px;
}

.mentions-input-box .mentions-autocomplete-list li > img,
.mentions-input-box .mentions-autocomplete-list li > div.icon {
  width: 16px;
  height: 16px;
  float: left;
  margin-top:5px;
  margin-right: 5px;
  -moz-background-origin:3px;

  border-radius:3px;
}

.mentions-input-box .mentions-autocomplete-list li em {
  font-weight: bold;
  font-style: none;
}

.mentions-input-box .mentions-autocomplete-list li:hover,
.mentions-input-box .mentions-autocomplete-list li.active {
  background-color: #f2f2f2;
}

.mentions-input-box .mentions-autocomplete-list li b {
  background: #ffff99;
  font-weight: normal;
}

.mentions-input-box .mentions {
  position: absolute;
  left: 1px;
  right: 0;
  top: 1px;
  bottom: 0;
  padding: 9px;
  color: #fff;
  overflow: hidden;

  white-space: pre-wrap;
  word-wrap: break-word;
}

.mentions-input-box .mentions > div {
  color: #fff;
  white-space: pre-wrap;
  width: 90%;
}

.mentions-input-box .mentions > div > strong {
  font-weight:normal;
  background: #d8dfea;
}

.mentions-input-box .mentions > div > strong > span {
  filter: progid:DXImageTransform.Microsoft.Alpha(opacity=0);
}

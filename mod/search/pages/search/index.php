<?php
/**
 * Elgg search page
 *
 * @todo much of this code should be pulled out into a library of functions
 */

// Search supports RSS
global $autofeed;
global $CONFIG;
$autofeed = true;

$i = 0;

// $search_type == all || entities || trigger plugin hook
$search_type = get_input('search_type', 'all');

$offset = get_input('offset');
$topic_name = get_input('topic_name');
$topic = get_input('topic');

$guid = get_loggedin_userid();

$access_ids = get_access_array($guid);

// @todo there is a bug in get_input that makes variables have slashes sometimes.
// @todo is there an example query to demonstrate ^
// XSS protection is more important that searching for HTML.
$query = stripslashes(get_input('q', get_input('tag', '')));
$fq = stripslashes(get_input('fq', get_input('tag', '')));
//(file_type:Word%20AND%20author_s:Administrator)

if($fq != "") {
	$fqs = split(" AND ", substr($fq,1,-1));
}
	
// @todo - create function for sanitization of strings for display in 1.8
// encode <,>,&, quotes and characters above 127
if (function_exists('mb_convert_encoding')) {
	$display_query = mb_convert_encoding($query, 'HTML-ENTITIES', 'UTF-8');
} else {
	// if no mbstring extension, we just strip characters
	$display_query = preg_replace("/[^\x01-\x7F]/", "", $query);
}
$display_query = htmlspecialchars($display_query, ENT_QUOTES, 'UTF-8', false);

// check that we have an actual query
if (!$query) {
	$title = sprintf(elgg_echo('search:results'), "\"$display_query\"");
	
	$body  = elgg_view_title(elgg_echo('search:search_error'));
	$body .= elgg_echo('search:no_query');
	$layout = elgg_view_layout('one_sidebar', array('content' => $body));
	echo elgg_view_page($title, $layout);

	return;
}

/***************** inizio codice aggiunta documenti da KnoBoos ******************/
 // 
  // 
  // Try to connect to the named server, port, and url
  // 
  $solr = new Apache_Solr_Service( '10.24.5.195', '80', '/solr' );
  $knoboosResult = "";
  $continue = true;
 
  if ( ! $solr->ping() ) {
    $knoboosResult.= 'Cannot connect to Solr service';
    $continue = false;
  } 
  $limit = 10;
	 
  if($continue) { 
	  //
	  // 
	  // Run some queries. 
	  //
	  
	  if(!isset($offset))
		$offset = 0;
	  
	   
	  $queries = array(
		$query ,
	  );
	  $acc_ids_string = "(";
	  foreach($access_ids as $access_id) {
		  $acc_ids_string .= " access_level:'".$access_id."' OR";
	  }
	  $acc_ids_string = substr($acc_ids_string, 0, -3).")";
	  
	  if($fq != "") {
		if($topic_name=="")
			$fqAccessId = $acc_ids_string. " AND ".substr($fq, 1, -1);				
		else
			$fqAccessId = $acc_ids_string. " AND ".$fq;				
	  } else
		$fqAccessId = $acc_ids_string;

	  if($topic != "")		
		$fqAccessId .= " AND ".$topic;	
		
	  $params = array("fq" => $fqAccessId);
	
	  foreach ( $queries as $query ) {
		$response = $solr->search( $query, $offset, $limit, $params );
		$numFound = $response->response->numFound;
		
		if ( $response->getHttpStatus() == 200 ) { 
		  // print_r( $response->getRawResponse() );
		   
		  if ( $numFound > 0 ) {
			//echo "$query <br />";
			$knoboosResult .= "<h1 class=\"search-heading-category\"></h1><ul class=\"elgg-list search-list\">";
			
			foreach ( $response->response->docs as $doc ) { 
				$temp = $doc->id;
				if($doc->categoria == "file" || $doc->categoria == "user" || $doc->categoria == "blog" || $doc->categoria == "bookmark" || $doc->categoria == "status" || $doc->categoria == "discussion" || $doc->categoria == "comment") {
					if(isset($doc->author_id)) {
						$urlImage = get_entity($doc->author_id)->getIcon('small');
					} else {
						$urlImage = get_entity($doc->id)->getIcon('small');
					}
					$url = $doc->file_path;
				} else {
					$urlImage = $CONFIG->url.'/_graphics/filetypes/'.getFormat($doc->name_file).'.png';
					$url = 'file://'.$doc->file_path;
				}
				
				if($doc->categoria == "status")
					$doc->name_file = "Status update";
				
				$knoboosResult .= '<li id="elgg-object-'.$doc->id.'" class="elgg-item">
				<div class="elgg-image-block clearfix">
					<div class="elgg-image"><img style="width:40px;" src="'.$urlImage.'"></div>
					<div class="elgg-body">
						<p class="mbn"><a href="'.$url.'"><strong class="">'.$doc->name_file.'</strong></a></p>'.str_replace("&#65533;","",$response->highlighting->$temp->content[0]).'</p>
					</div>
				</div>
				</li>';
			}
			
			
			/*if($numFound > 5)
				$knoboosResult .= '<li class="elgg-item"><a href="#" onclick="sendRequest();">+'.($numFound - 5).' more documents</a></li>';*/
			$knoboosResult .= "</ul>";
		  }
		} else {
		  $knoboosResult.= 'Solr service not responding';
		}
	  }
	  
	  
	  
/***************** fine codice aggiunta documenti da KnoBoos ******************/

// get limit and offset.  override if on search dashboard, where only 2
// of each most recent entity types will be shown.

// set up search params
$params = array(
	'query' => $query,
	'offset' => $offset,
	'limit' => $limit,
	'sort' => $sort,
	'order' => $order,
	'search_type' => $search_type,
	'type' => $entity_type,
	'subtype' => $entity_subtype,
	'owner_guid' => $owner_guid,
	'container_guid' => $container_guid,
	'pagination' => ($search_type == 'all') ? FALSE : TRUE
);

	$fqParam = "";

	if(count($fqs)>0)
		foreach ($fqs as $corrente) {
			if($corrente!="")// && strpos($corrente,'access_level') === false)
				$fqParam.= $corrente." AND ";
		}

	foreach ( $response->facet_counts->facet_fields as $name => $facets ) {
		if($name != "access_level") {
			$menu_item = new ElggMenuItem($name, elgg_echo("knoboos:".$name), "#");
			$menu_item->style = "font-weight:bold";
			$menu_items_facet = array();
			foreach ($facets as $key => $value) {
				if($key != "_empty_" && strpos($fq,$key)===FALSE) {
					$tempfqParam = $fqParam.$name.":\"".$key."\"";
					$facet_item = new ElggMenuItem($key, $key." (".$value.")", "#");
					$facet_item->onclick = "$(\"#facet-".$i."\").submit();";
					$menu_items_facet[] = $facet_item;
					
					$form_prefix_id = "facet-";
					$input = "<input type='text' name='fq' value='(".$tempfqParam.")'>";
					$form_string = getFormForSearch($form_prefix_id, $i, $input, $topic, $topic_name, $query);
					
					$form_item = new ElggMenuItem($key."-form", $form_string, false);
					$menu_items_facet[] = $form_item;
					$i++;
				}
			}
			$menu_item->setChildren($menu_items_facet);
			elgg_register_menu_item('page', $menu_item);
		}
	}



	foreach ( $response->facet_counts->facet_ranges as $name => $last_modified ) {
		$menu_item = new ElggMenuItem($name, elgg_echo("knoboos:".$name), "#");
		$menu_item->style = "font-weight:bold";
		$gap = $last_modified->gap;
		$start = $last_modified->start;
		$before = $last_modified->before;
		$year_items = array();
		if(count((array)$last_modified->counts) > 0 && $before > 0) {
			$tempfqParam = $fqParam.'last_modified:[* TO '.$start.'}';
			$before_item = new ElggMenuItem($start, "Prima del ".getYear($start)." (".$before.")", "#");
			$before_item->onclick = "$(\"#facet-".$i."\").submit();";
			$year_items[] = $before_item;
			
			$form_prefix_id = "facet-";
			$input = "<input type='text' name='fq' value='(".$tempfqParam.")'>";
			$form_string = getFormForSearch($form_prefix_id, $i, $input, $topic, $topic_name, $query);
					
			$form_item = new ElggMenuItem($start."-form", $form_string, false);
			$year_items[] = $form_item;
			$i++;
		}
		$last_year = null;
		$current_year = null;
		foreach ($last_modified->counts as $key => $value) {
			$current_year = getYear($key);
			if($current_year != $last_year) {
				$year_items[] = new ElggMenuItem($current_year, $current_year, "#");
			}
			
			if($key != "_empty_" && strpos($fq,$key)===FALSE) {
				$tempfqParam = $fqParam.$name.":[".$key." TO ".$key.$gap."}";
				$menu_item_facet = new ElggMenuItem($key, getMonth($key)." (".$value.")", "#");
				$menu_item_facet->onclick = "$(\"#facet-".$i."\").submit();";
				end($year_items)->addChild($menu_item_facet);
							
				$form_prefix_id = "facet-";
				$input = "<input type='text' name='fq' value='(".$tempfqParam.")'>";
				$form_string = getFormForSearch($form_prefix_id, $i, $input, $topic, $topic_name, $query);
					
				$form_item = new ElggMenuItem($key."-form", $form_string, false);
				end($year_items)->addChild($form_item);
				$i++;
			
			}
			$last_year = $current_year;
		}
		$menu_item->setChildren($year_items);
		elgg_register_menu_item('page', $menu_item);
	}

	$menu_item_script = new ElggMenuItem("carrot", "<script>
							$(document).ready(function() { 
								$.post( 'request?q=".$query."', { fq: '".$fq."' }).done(function( data ) {
									$(\".elgg-menu-item-carrot\").html( data);
								});
							});
							</script><img style='margin-left:40px;' src='/nexu/_graphics/ajax_loader.gif'>", "#");

	elgg_register_menu_item('page', $menu_item_script);

	// start the actual search
	$results_html = '';

	// highlight search terms
	if ($search_type == 'tags') {
		$searched_words = array($display_query);
	} else {
		$searched_words = search_remove_ignored_words($display_query, 'array');
	}
	$highlighted_query = search_highlight_words($searched_words, $display_query);

	$filtro = "";

	if(count($fqs) > 0 || $topic != "") {
		$filtro = "- Filtro attivo";
		$attivi = "<br><span style=\"font-size:11px; font-weight:normal;\">Clicca sul link per disattivare "; 
		if($topic != "") {
			$attivi .= " &gt; <a href='#' onclick='$(\"#facet-".$i."\").submit();'>".$topic_name."</a>";
			$input = "<input type='text' name='fq' value='".$fq."'>";	
			$form_string =  getFormForSearch("facet-", $i, $input, "", "", $query);		
			$attivi .= $form_string;
			
			$i++;
		}
		$firstTime = true;
		foreach ($fqs as $corrente) {
			$newFq = strstr($fq, $corrente, true); 
			if(substr($corrente,0,3)=="id:")
				$corrente = $topic_name;
			
			$attivi .= " &gt; <a href='#' onclick='$(\"#facet-".$i."\").submit();'>".$corrente."</a>";
			
			$input = "";
			if($firstTime) {
				$firstTime = false;
			} else {
				$input .= "<input type='text' name='fq' value='(".substr($newFq,1,-4).")'>";	
			}
			$form_string =  getFormForSearch("facet-", $i, $input, $topic, $topic_name, $query);		
			$attivi .= $form_string;
			$i++;
		}
		$attivi.="</span>";
	}

	$pageNumber = $offset/$limit+1;

	$numResultsLabel = ($pageNumber*$limit > $numFound)? $numFound : $pageNumber*$limit;

	$body = elgg_view_title( elgg_echo('searchnumber',array($numResultsLabel,$numFound))." ". elgg_echo('search:results', array("\"$highlighted_query\""))." ".$filtro.$attivi);

	$correctlySpelled = $response->spellcheck->suggestions->correctlySpelled;
	$wordSuggested = $response->spellcheck->suggestions->$query->suggestion[0]->word;

	if($correctlySpelled===false && $wordSuggested != "") {	
		$body .= "<p style=\"font-size: medium;margin-top: 10px;margin-left: 10px;\">Forse cercavi <a href='".elgg_get_site_url()."search?q=".$wordSuggested."'>".$wordSuggested."</a>?</p>";
	}

}
/*if (!$results_html) {
	$body .= elgg_view('search/no_results');
} else {
	$body .= $results_html;
}*/

$body .= $knoboosResult;

$body .=  elgg_view("navigation/pagination", array("offset" => $offset, "limit" => $limit, "count" => $numFound));

// this is passed the original params because we don't care what actually
// matched (which is out of date now anyway).
// we want to know what search type it is.
$layout_view = search_get_search_view($params, 'layout');
$layout = elgg_view($layout_view, array('params' => $params, 'body' => $body));

$title = elgg_echo('search:results', array("\"$display_query\""));

echo elgg_view_page($title, $layout);

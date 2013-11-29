<?php
/**
 * Elgg search page
 *
 * @todo much of this code should be pulled out into a library of functions
 */
$query = stripslashes(get_input('q', get_input('tag', '')));
$fq = stripslashes(get_input('fq', get_input('tag', '')));
$menu_item = new ElggMenuItem("topic", elgg_echo("knoboos:topic"), "#");
$menu_item->style = "font-weight:bold;";

$solr = new Apache_Solr_Service( '10.24.4.148', '80', '/solr' );
$continue = true;

$response = "";
if ( ! $solr->ping() ) {
	$response.= 'Solr service not responding.';
	$continue = false;
}

$topic == "";

if($continue) { 
  //
  // 
  // Run some queries. 
  //
	$queries = array(
		$query ,
	);
	$params = array("fq" => $fq);
	
	try {
			
		foreach ( $queries as $query ) {
			$response = $solr->clustering( $query, $params );
			$numFound = count($response->clusters);
			
			if ( $response->getHttpStatus() == 200 ) { 
			  // print_r( $response->getRawResponse() );
			   
				if ( $numFound > 0 ) {
					$i = 0;
					foreach ( $response->clusters as $cluster ) {				
						$topic_item = new ElggMenuItem($cluster->labels[0], $cluster->labels[0], "#");
						$topic_item->onclick = "$(\"#target-".$i."\").submit()";
						$menu_item->addChild($topic_item);
						
						$topic = "id:(";
						foreach( $cluster->docs as $doc ) {
							$topic .= "\"".$doc."\" OR ";
						}
						$topic = substr($topic, 0, -3).")";
						$topic = "(".$topic.")";
						
						$form_prefix_id = "target-";
						$input = "<input type='text' name='topic' value='".$topic."'>";
						$input .= "<input type='text' name='topic_name' value='".$cluster->labels[0]."'>";
						if($fq!="")
							$input .= "<input type='text' name='fq' value='".$fq."'>";
						
						$form_string = getFormForSearch($form_prefix_id, $i, $input, "", "", $query);
					
						
						$form_item = new ElggMenuItem($cluster->labels[0]."-form", $form_string, false);
						$menu_item->addChild($form_item);
						$i++;
					}
				}
				$response = elgg_view('navigation/menu/elements/item', array('item' => $menu_item));
			} else {
				$response = 'clustering non disponibile';
				error_log("http status not 200!!");
			}
		}
	} catch (Exception $e) {
		$response = 'clustering non disponibile';
		error_log("exception cached!!");
	}
}

echo $response;
<?php

if (!elgg_in_context('starrating')) {
	return true;
}

echo elgg_view('output/starrating', $vars);
<?php

$users = array( 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, 78, 79, 80, 81, 82, 83, 84, 85, 86, 87, 88, 89, 90, 91, 92, 93, 94, 95, 96, 97, 98, 99, 100, 101, 102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122, 123, 124, 125, 126, 127, 128, 129, 130, 131, 132, 133, 134, 135, 136, 137, 138, 139, 140, 141, 142, 145, 146, 147, 149, 150, 152, 4, 155, 156, 14, 40, 36, 39, 37, 157, 144, 26, 158, 159, 160, 161, 162, 163, 164, 35, 165, 167, 2, 168, 169, 170, 171, 172, 173, 175, 176, 177, 178, 179, 180, 181, 182, 184, 185, 186, 187, 190, 191, 192, 194, 196, 197, 44, 200, 201, 202, 203, 204, 205, 206, 209, 210, 212, 213, 214, 215, 216, 217, 218, 219, 220, 221 );

foreach ( $users as $user_id ) {
	$data = get_userdata( $user_id );
	$meta = get_user_meta( $user_id );

	// If user exists (checking for org name)
	if ( $meta['organization'][0] ) {

		$address = array(
			'address-1' => $meta['paupress_address_one_1'][0],
			'address-2' => $meta['paupress_address_two_1'][0],
			'city' => $meta['paupress_address_city_1'][0],
			'state' => $meta['paupress_address_state_1'][0],
			'zip' => $meta['paupress_address_postal_code_1'][0],
		);

		$postarr = array(
			'ID' => 0,
			'post_title' => $meta['organization'][0],
			'post_content' => $meta['description'][0],
			'post_type' => 'inn_member',
			'post_status' => 'publish',
			'tax_input'    => array(
				'ppu_focus_areas' => maybe_unserialize( $meta['ppu_focus_areas'][0] ),
				'pauinn_project_tax' => maybe_unserialize( $meta['pauinn_project_tax'][0] ),
		    ),
			'meta_input' => array(
				'_thumbnail_id' => $meta['paupress_pp_avatar'][0],
				'_year_founded' => $meta['inn_founded'][0],
				'_inn_join_year' => $meta['inn_since'][0],
				'_email' => $data->data->user_email,
				'_url' => $data->data->user_url,
				'_donate_url' => $meta['inn_donate'][0],
				'_rss_feed' => $meta['inn_rss'][0],
				'_twitter_url' => $meta['inn_twitter'][0],
				'_facebook_url' => $meta['inn_facebook'][0],
				'_youtube_url' => $meta['inn_youtube'][0],
				'_google_plus_url' => $meta['inn_googleplus'][0],
				'_phone_number' => $meta['inn_phone'][0],
				'_address' => $address,
		    ),
		);

		// Insert Post
		// wp_insert_post( $postarr );


		// Check for missing fields
		$migration_data = array(
			'address-line-1' => $meta['paupress_address_one_1'][0],
			'address-line-2' => $meta['paupress_address_two_1'][0],
			'city' => $meta['paupress_address_city_1'][0],
			'state' => $meta['paupress_address_state_1'][0],
			'zip' => $meta['paupress_address_postal_code_1'][0],
			'taxonomy_ppu_focus_areas' => maybe_unserialize( $meta['ppu_focus_areas'][0] ),
			'taxonomy_pauinn_project_tax' => maybe_unserialize( $meta['pauinn_project_tax'][0] ),
			'_thumbnail_id' => $meta['paupress_pp_avatar'][0],
			'_year_founded' => $meta['inn_founded'][0],
			'_inn_join_year' => $meta['inn_since'][0],
			'_email' => $data->data->user_email,
			'_url' => $data->data->user_url,
			'_donate_url' => $meta['inn_donate'][0],
			'_rss_feed' => $meta['inn_rss'][0],
			'_twitter_url' => $meta['inn_twitter'][0],
			'_facebook_url' => $meta['inn_facebook'][0],
			'_youtube_url' => $meta['inn_youtube'][0],
			'_google_plus_url' => $meta['inn_googleplus'][0],
			'_phone_number' => $meta['inn_phone'][0],
			'_address' => $address,
		);

		// Output an 'X' for evey field present, and a blank value for every empty field value.
		echo $meta['organization'][0] . ',';
		foreach ( $migration_data as $key => $migration_data_item ) {
			echo empty( $migration_data_item ) ? ',' : 'X,';
		}
		echo '<br />';
		continue;

		/*
		 * List view of items missing
		$missing_data = '';
		foreach ( $migration_data as $key => $migration_data_item ) {
			if ( is_null( $migration_data_item ) ) {
				$missing_data[] = $key;
			}
		}

		if ( $missing_data ) {
			echo '<h1>' . $meta['organization'][0] . ' - ID #' . $user_id . '</h1>';
			echo '<ul>';
			foreach ( $missing_data as $missing_data_item ) {
				echo '<li>' . $missing_data_item . '</li>';
			}
			echo '</ul>';
		}
		*/
	} else {
		$does_not_exist[] = $user_id;
	}
}

if ( $does_not_exist ) {
	echo '<h2>These users do not exist:</h2>';
	echo '<ul>';
	foreach ( $does_not_exist as $item ) {
		echo '<li>' . $item . '</li>';
	}
	echo '</ul>';
}

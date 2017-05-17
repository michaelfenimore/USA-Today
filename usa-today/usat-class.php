<?php
/**
 * The core class for appending the most recent post to all article/post types.
 *
 * @link       www.usatoday.com
 * @since      1.0.0
 * @package    usa-today
 * @author     Michael Fenimore <mfenimore70@gmail.com>
 */

if ( !class_exists( 'Usa_Today' ) ) {
	class Usa_Today {

		public static function init() {
			//Everything is handled with Usat_Activator in the usat-activate-class.php
		}

		public static function get_most_recent_article( $content ) {
			//Only apply to POST pages.
			if ( !is_singular( 'post' ) ) return $content;

			//Only retrieve one post and make sure it is published.
			$args = array (
				'numberposts' => 1,
				'post_type' => 'post',
				'post_status' => 'publish',
			);

			$usta_recent_article = wp_get_recent_posts( $args );

			//Make sure no empty values are returned. If some are empty they can be given default values. Default values will need business approval.
			if ( !empty( $usta_recent_article ) && is_array( $usta_recent_article ) ) {
				$usta_latest_post['title'] = !empty( $usta_recent_article[0]['post_title'] ) ? $usta_recent_article[0]['post_title'] : 'Most Recent Article' ;
				$usta_latest_post['author'] = !empty( $usta_recent_article[0]['post_author'] ) ? get_the_author_meta( 'display_name', $usta_recent_article[0]['post_author'] ) : 'USA Today' ;
				$usta_latest_post['thumbnail'] = get_the_post_thumbnail( $usta_recent_article[0][ID]) ;

				//Multiple categories can be returned for a post. This grabs the first one. If necessary can check to make sure its a parent category.
				$categories = wp_get_post_categories( $usta_recent_article[0][ID] );
				if ( !empty( $categories ) ) {
					$first_category_array = get_category( $categories[0] );
					$usta_latest_post['category_name'] = $first_category_array->name;
				}

				//Convert the posts timestamp into unix time to get time difference
				$unix_timestamp = strtotime( $usta_recent_article[0]['post_modified_gmt'] );
				$usta_latest_post['time'] = human_time_diff( $unix_timestamp, current_time( 'timestamp' ) ) . ' ago';
			}

			//Define what tags are valid when outputting image tag (wp_kses). VIP requires escaping.
			$valid_image_tags = array(
				'img' => array(
					'class' => array(),
					'src' => array(),
					'alt' => array(),
				),
			);

			$html = '<div id=usat_main_container>
				<div class="usat-thumbnail">' . wp_kses( $usta_latest_post['thumbnail'], $valid_image_tags ) . '</div>
				<div class="usat-category">' . esc_html( $usta_latest_post['category_name'] ) . '
					<span class="usat-time mini"> | ' . esc_html( $usta_latest_post['time'] ) . '</span>
				</div>
				<h2 class="usat-title">' . esc_html( $usta_latest_post['title'] ) . '</h2>
				<div class="usat-metadata">
					<div class="usat-byline">By: <span class="usat-name">' . esc_html( $usta_latest_post['author'] ) . '</span>
						<span class="usat-time">' . esc_html( $usta_latest_post['time'] ) . '</span>
					</div>
				</div>
			</div>';

			$content = $content . $html;

			return $content;
		}
	}
}
?>
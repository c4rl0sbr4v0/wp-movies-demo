<?php
$post               = get_post();
$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class'      => 'wpmovies-tabs',
		'wp-context' => '{ "tab": "images" }',
	)
);
$images             = get_post_meta( $post->ID, '_wpmovies_images', true );
$videos             = get_post_meta( $post->ID, '_wpmovies_videos', true );

// Once available, we may want to initialize the selectors in SSR:
//
// store([
// ['selectors' => [
// 'wpmovies' => [
// 'isImagesTab' => true,
// 'isVideosTab' => false
// ]
// ]]
// ]);
?>

<div <?php echo $wrapper_attributes; ?>>
	<ul>
		<li wp-on:click="actions.wpmovies.showImagesTab" wp-class:wpmovies-active-tab="selectors.wpmovies.isImagesTab" class="wpmovies-tabs-title">Images</li>
		<li wp-on:click="actions.wpmovies.showVideosTab" wp-class:wpmovies-active-tab="selectors.wpmovies.isVideosTab" class=" wpmovies-tabs-title">Videos</li>
	</ul>
	<wp-show when="selectors.wpmovies.isImagesTab">
		<div class="wpmovies-media-scroller wpmovies-images-tab">
			<?
			foreach (json_decode($images, true) as $image_id) {
				$image_url = wp_get_attachment_image_url($image_id, '');
			?>
				<img src="<? echo $image_url ?>">
			<?
			}
			?>
		</div>
	</wp-show>
	<wp-show when="selectors.wpmovies.isVideosTab">
		<div class="wpmovies-media-scroller wpmovies-videos-tab">
			<?
			foreach (json_decode($videos, true) as $video) {
				$video_id = substr($video["url"], strpos($video["url"], "?v=") + 3);
			?>
				<div class="wpmovies-tabs-video-wrapper" wp-context='{ "videoId": "<?php echo $video_id; ?>" }'>
					<div wp-on:click="actions.wpmovies.setVideo">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#ffffff" class="play-icon">
							<path d="M3 22v-20l18 10-18 10z" />
						</svg>
					</div>
					<img src="<? echo 'https://img.youtube.com/vi/' . $video_id . '/0.jpg' ?>">
				</div>
			<?
			}
			?>
		</div>
	</wp-show>
</div>
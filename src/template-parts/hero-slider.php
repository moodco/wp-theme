<?php
/**
 * Hero Slider — Swipeable featured posts.
 * Uses CSS scroll-snap for smooth native scrolling.
 * Minimal JS for pagination dots.
 *
 * Mobile: Square (1:1) cards
 * Desktop: Landscape cards
 * 
 * @param array $args['hero_count'] Number of posts (default 5)
 */
$hero_count = $args['hero_count'] ?? moodco_config('homepage.hero_count', 5);

$hero_posts = new WP_Query([
    'posts_per_page' => $hero_count,
    'post_status'    => 'publish',
    'meta_query'     => [['key' => '_thumbnail_id']],
]);

if (!$hero_posts->have_posts()) return;

$posts_array = $hero_posts->posts;
?>

<section class="hero-slider-section">
    <div class="container">
        <div class="hero-slider" data-hero-slider>
            <div class="hero-slider__track">
                <?php foreach ($posts_array as $post): 
                    $cats = get_the_category($post->ID);
                    $cat = $cats[0] ?? null;
                ?>
                <article class="hero-slider__slide">
                    <a href="<?php echo get_permalink($post); ?>" class="hero-slider__link">
                        <div class="hero-slider__img">
                            <img src="<?php echo get_the_post_thumbnail_url($post, 'large'); ?>" 
                                 alt="<?php echo esc_attr($post->post_title); ?>"
                                 loading="eager">
                        </div>
                        <div class="hero-slider__overlay">
                            <?php if ($cat): ?>
                                <span class="hero-slider__cat"><?php echo esc_html($cat->name); ?></span>
                            <?php endif; ?>
                            <h2 class="hero-slider__title"><?php echo esc_html($post->post_title); ?></h2>
                            <div class="hero-slider__meta">
                                <span class="meta-item author-link-trigger" data-author-url="<?php echo get_author_posts_url($post->post_author); ?>">
                                    <?php echo moodco_svg_icon('users', 'meta-icon'); ?>
                                    <?php echo get_the_author_meta('display_name', $post->post_author); ?>
                                </span>
                                
                                <span class="meta-divider">&middot;</span>
                                
                                <span class="meta-item">
                                    <?php echo moodco_svg_icon('calendar', 'meta-icon'); ?>
                                    <?php echo moodco_time_ago($post->ID); ?>
                                </span>
                            </div>
                        </div>
                    </a>
                </article>
                <?php endforeach; ?>
            </div>
 
            <?php if (count($posts_array) > 1): ?>
            <div class="hero-slider__dots">
                <?php foreach ($posts_array as $index => $post): ?>
                    <button class="hero-slider__dot <?php echo $index === 0 ? 'active' : ''; ?>" 
                            data-slide="<?php echo $index; ?>"
                            aria-label="Go to slide <?php echo $index + 1; ?>"></button>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <!-- Arrows -->
            <button class="hero-slider__prev"><?php echo moodco_svg_icon('arrow-left'); ?></button>
            <button class="hero-slider__next"><?php echo moodco_svg_icon('arrow-right'); ?></button>
        </div>
    </div>
</section>

<?php wp_reset_postdata(); ?>

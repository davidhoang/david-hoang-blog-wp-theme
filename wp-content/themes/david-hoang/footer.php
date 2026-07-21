<?php
/**
 * The footer template file
 *
 * @package DavidHoang
 */
?>

    <footer id="colophon" class="site-footer" role="contentinfo" aria-label="Site footer">
        <div class="site-container">
            <section class="footer-section-email">
                <a href="mailto:david@davidhoang.com" aria-label="Email David Hoang">david[at]davidhoang.com</a>
            </section>
            <section aria-label="Footer navigation">
                <div class="footer-grid">
                    <div class="footer-col">
                        <h4>Social</h4>
                        <ul role="list">
                            <li><a href="https://github.com/davidhoang" target="_blank" rel="noopener noreferrer">GitHub</a></li>
                            <li><a href="https://linkedin.com/in/dhoang2" target="_blank" rel="noopener noreferrer">LinkedIn</a></li>
                            <li><a href="https://sublime.app/davidhoang" target="_blank" rel="noopener noreferrer">Sublime</a></li>
                            <li><a href="https://twitter.com/davidhoang" target="_blank" rel="noopener noreferrer">Twitter</a></li>
                        </ul>
                    </div>
                    <div class="footer-col">
                        <h4>Links</h4>
                        <ul role="list">
                            <li><a href="https://careerodyssey.com" target="_blank" rel="noopener noreferrer">Career Odyssey</a></li>
                            <li><a href="https://curius.app/david-hoang" target="_blank" rel="noopener noreferrer">Curius</a></li>
                            <li><a href="https://www.proofofconcept.pub" target="_blank" rel="noopener noreferrer">Newsletter</a></li>
                            <li><a href="https://blog.davidhoang.com">Personal&nbsp;Blog</a></li>
                            <li><a href="<?php echo esc_url(get_bloginfo('rss2_url')); ?>" type="application/rss+xml">RSS</a></li>
                            <li><a href="https://substack.com/@davidhoang/notes" target="_blank" rel="noopener noreferrer">Substack Notes</a></li>
                        </ul>
                    </div>
                    <div class="footer-col">
                        <h4>Resources</h4>
                        <ul role="list">
                            <li><a href="https://www.davidhoang.com/about">About</a></li>
                            <li><a href="https://www.davidhoang.com/notes">Notes</a></li>
                            <li><a href="https://www.davidhoang.com/now">Now</a></li>
                            <li><a href="https://www.davidhoang.com/design-resources">Design Resources</a></li>
                        </ul>
                    </div>
                </div>
            </section>
            <section class="footer-section-copyright">
                <p>&copy; 2002-<?php echo date('Y'); ?> David Hoang. All rights reserved. "Sometimes to create, one must first destroy." &mdash;David, Prometheus</p>
            </section>
        </div>
    </footer>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>

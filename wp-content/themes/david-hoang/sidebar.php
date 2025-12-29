<?php
/**
 * Sidebar template
 *
 * @package DavidHoang
 */
?>

<aside id="secondary" class="sidebar">
    <?php
    if (is_active_sidebar('sidebar-1')) {
        dynamic_sidebar('sidebar-1');
    } else {
        // Fallback content if no widgets are added
        ?>
        <div class="sidebar-section">
            <h3 class="sidebar-title">About</h3>
            <p>Writing about design, technology, and building products.</p>
        </div>
        
        <div class="sidebar-section">
            <h3 class="sidebar-title">Newsletter</h3>
            <p>Subscribe to my newsletter Proof of Concept, sent every Sunday.</p>
            <p><a href="https://proofofconcept.substack.com" class="sidebar-link">Subscribe →</a></p>
        </div>
        
        <div class="sidebar-section">
            <h3 class="sidebar-title">Elsewhere</h3>
            <ul class="sidebar-list">
                <li><a href="https://twitter.com/davidhoang">Twitter</a></li>
                <li><a href="https://github.com/davidhoang">GitHub</a></li>
                <li><a href="https://www.linkedin.com/in/davidhoang">LinkedIn</a></li>
                <li><a href="<?php echo esc_url(get_bloginfo('rss2_url')); ?>">RSS Feed</a></li>
            </ul>
        </div>
        
        <div class="sidebar-section">
            <p class="sidebar-email">david[at]davidhoang.com</p>
        </div>
        
        <div class="sidebar-section">
            <h4 class="sidebar-subtitle">Social</h4>
            <ul class="sidebar-list">
                <li><a href="https://github.com/davidhoang">GitHub</a></li>
                <li><a href="https://www.linkedin.com/in/davidhoang">LinkedIn</a></li>
                <li><a href="https://sublime.com/davidhoang">Sublime</a></li>
                <li><a href="https://twitter.com/davidhoang">Twitter</a></li>
            </ul>
        </div>
        
        <div class="sidebar-section">
            <h4 class="sidebar-subtitle">Links</h4>
            <ul class="sidebar-list">
                <li><a href="https://careerodyssey.com">Career Odyssey</a></li>
                <li><a href="https://curius.app/davidhoang">Curius</a></li>
                <li><a href="https://proofofconcept.substack.com">Newsletter</a></li>
                <li><a href="https://blog.davidhoang.com">Personal Blog</a></li>
                <li><a href="<?php echo esc_url(get_bloginfo('rss2_url')); ?>">RSS</a></li>
                <li><a href="https://substack.com/@davidhoang/notes">Substack Notes</a></li>
            </ul>
        </div>
        
        <div class="sidebar-section">
            <h4 class="sidebar-subtitle">Resources</h4>
            <ul class="sidebar-list">
                <li><a href="https://www.davidhoang.com/about">About</a></li>
                <li><a href="https://www.davidhoang.com/now">Now</a></li>
            </ul>
        </div>
        
        <div class="sidebar-section sidebar-footer">
            <p class="sidebar-copyright">© 2002-<?php echo date('Y'); ?> David Hoang. All rights reserved.</p>
            <p class="sidebar-quote">"Sometimes to create, one must first destroy." —David, Prometheus</p>
        </div>
        <?php
    }
    ?>
</aside>

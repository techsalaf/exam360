<?php

return [
    'save' => 'Save Changes',
    'save_config' => 'Save Configuration',
    'generate_now' => 'Generate Sitemap Now',
    
    // ALERTS (Moved to root level so Controller can find them)
    'alerts' => [
        'remove_title' => 'Remove Banner?',
        'remove_text' => 'This will remove the current SEO banner upon saving.',
        'yes_remove' => 'Yes, Remove',
        'cancel' => 'Cancel',
        'invalid_group' => 'Invalid settings group.',
        'updated_success' => 'SEO settings updated successfully.',
        'sitemap_generated' => 'Sitemap generated successfully.',
        'sitemap_failed' => 'Failed to generate sitemap: :error',
        'sitemap_not_found' => 'Sitemap file not found. Please generate it first.',
    ],

    'defaults' => [
        'desc' => 'The best AI-powered assessment and learning platform.',
        'keywords' => 'exam, ai, assessment, learning',
    ],

    'config' => [
        'title' => 'SEO Configuration',
        'desc' => 'Configure meta tags, social sharing visuals, and analytics tracking.',
        'meta_title' => 'Meta Tags & Visuals',
        'meta_desc' => 'Optimize how your site appears in search results and social feeds.',
        'meta_title_label' => 'Meta Title (Max 60 chars)',
        'meta_title_ph' => 'Ex: ZiExam AI - Learning Platform',
        'meta_desc_label' => 'Meta Description (Max 160 chars)',
        'meta_desc_ph' => 'A brief summary of your site\'s content.',
        'keywords_label' => 'Keywords (Comma Separated)',
        'keywords_ph' => 'keywords, separated, by, commas',
        
        'analytics_title' => 'Analytics & Verification',
        'ga_label' => 'Google Analytics Tracking ID',
        'ga_ph' => 'UA-XXXXXXXXX-Y or G-XXXXXXXXX',
        'ga_help' => 'Insert your Google Analytics/GA4 Measurement ID.',
        
        'banner_title' => 'Social Share Banner',
        'banner_help' => 'Recommended Size: 1200x630px. Used for OpenGraph / Twitter Cards.',
        'delete_banner_title' => 'Delete current banner',
        'no_banner' => 'No banner uploaded.',
    ],

    'sitemap' => [
        'title' => 'Sitemap Configuration',
        'desc' => 'Control search engine crawling and manage the sitemap XML file.',
        'crawling_title' => 'Crawling Rules',
        'crawling_desc' => 'Define how bots interact with your site structure.',
        'robots_label' => 'Robots Meta Tag',
        'robots_options' => [
            'index_follow' => 'Index and Follow (Default)',
            'noindex_follow' => 'No Index, but Follow links',
            'index_nofollow' => 'Index, but No Follow links',
            'noindex_nofollow' => 'No Index and No Follow',
        ],
        'robots_help' => 'Controls site-wide indexing behavior.',
        
        'status_title' => 'Sitemap Status',
        'file_url' => 'File URL:',
        'last_gen' => 'Last Generated:',
        'never' => 'Never',
        'download_xml' => 'Download XML',
        'info_text' => 'The <strong>sitemap.xml</strong> file helps search engines discover your pages. After generating, submit the full URL above to Google Search Console.',
    ],
];
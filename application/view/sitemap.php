<?php
$host = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/";
?>
<urlset
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
<url>
  <loc><?= $host ?></loc>
  <lastmod><?= date('c') ?></lastmod>
  <priority>1.00</priority>
</url>
<?php
  foreach ($this->entries as $entry) { ?>  
<url>
  <loc><?= $host . $entry->loc ?></loc>
  <lastmod><?= date('c', $entry->lastmod) ?></lastmod>
  <priority>0.80</priority>
</url>
<?php } ?>
</urlset>
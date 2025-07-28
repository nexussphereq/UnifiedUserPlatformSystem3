<?php
function renderNwfNode($node) {
    if (!is_array($node) || !isset($node['type'])) {
        return '';
    }
    if ($node['type'] === 'text') {
        // Escape text content
        return htmlspecialchars($node['text'] ?? '');
    } elseif ($node['type'] === 'element') {
        $tag = htmlspecialchars($node['tag'] ?? 'div');
        $attrs = '';
        if (!empty($node['attributes']) && is_array($node['attributes'])) {
            foreach ($node['attributes'] as $k => $v) {
                // Only allow safe attributes, validate src for media
                if (in_array($k, ['class', 'id', 'src', 'alt', 'controls', 'width', 'height', 'type', 'href', 'target', 'rel'])) {
                    // For src attributes, allow only data: URIs or http(s)
                    if ($k === 'src') {
                        if (!preg_match('#^(data:|https?://)#i', $v)) continue;
                    }
                    $attrs .= ' ' . htmlspecialchars($k) . '="' . htmlspecialchars($v) . '"';
                }
            }
        }
        // Render children recursively
        $childrenHtml = '';
        if (!empty($node['children']) && is_array($node['children'])) {
            foreach ($node['children'] as $child) {
                $childrenHtml .= renderNwfNode($child);
            }
        }
        // Void elements: img, br, source - self-close
        $voidTags = ['img', 'br', 'hr', 'source', 'input', 'meta', 'link'];
        if (in_array(strtolower($tag), $voidTags)) {
            return "<$tag$attrs />";
        }
        return "<$tag$attrs>$childrenHtml</$tag>";
    }
    return '';
}

// Convenience: render full content array
function renderNwfContentArray($content) {
    $html = '';
    foreach ($content as $node) {
        $html .= renderNwfNode($node);
    }
    return $html;
}

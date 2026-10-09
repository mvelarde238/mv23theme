<?php
namespace Core\Builder\Component\Settings;

use Core\Builder\Component\Settings\Repeater_Table;

/**
 * Builds the repeater field used to edit query parameters in the listing component. 
 */
class Query_Parameters extends Repeater_Table
{
    public static function getOptions()
    {
        return [
            // most common parameters
            ['key' => 'posts_per_page', 'label' => 'Posts per page', 'type' => 'number', 'default' => 3],
            ['key' => 'order', 'label' => 'Order', 'type' => 'text', 'suggestions' => ['ASC', 'DESC'], 'default' => 'DESC'],
            ['key' => 'orderby', 'label' => 'Order by', 'type' => 'text', 'suggestions' => ['date', 'title', 'name', 'rand', 'menu_order'], 'default' => 'date'],
            ['key' => 'offset', 'label' => 'Offset', 'type' => 'number', 'default' => 0],
            ['key' => 'paged', 'label' => 'Paged', 'type' => 'number', 'default' => 1],
            ['key' => 'post_status', 'label' => 'Post status', 'type' => 'text', 'suggestions' => ['publish', 'draft', 'pending', 'future', 'private', 'inherit', 'trash'], 'default' => 'publish', 'list_type' => 'string'],
            // author parameters
            ['key' => 'author', 'label' => 'Author', 'type' => 'text', 'default' => ''],
            ['key' => 'author_name', 'label' => 'Author Name', 'type' => 'text', 'default' => ''],
            ['key' => 'author__in', 'label' => 'Author In', 'type' => 'text', 'default' => '', 'list_type' => 'integer'],
            ['key' => 'author__not_in', 'label' => 'Author Not In', 'type' => 'text', 'default' => '', 'list_type' => 'integer'],
            // category parameters
            ['key' => 'cat', 'label' => 'Category', 'type' => 'text', 'default' => ''],
            ['key' => 'category_name', 'label' => 'Category Name', 'type' => 'text', 'default' => ''],
            ['key' => 'category__in', 'label' => 'Category In', 'type' => 'text', 'default' => '', 'list_type' => 'integer'],
            ['key' => 'category__not_in', 'label' => 'Category Not In', 'type' => 'text', 'default' => '', 'list_type' => 'integer'],
            ['key' => 'category__and', 'label' => 'Category And', 'type' => 'text', 'default' => '', 'list_type' => 'integer'],
            // tag parameters
            ['key' => 'tag', 'label' => 'Tag', 'type' => 'text', 'default' => ''],
            ['key' => 'tag_id', 'label' => 'Tag ID', 'type' => 'text', 'default' => ''],
            ['key' => 'tag__in', 'label' => 'Tag In', 'type' => 'text', 'default' => '', 'list_type' => 'integer'],
            ['key' => 'tag__not_in', 'label' => 'Tag Not In', 'type' => 'text', 'default' => '', 'list_type' => 'integer'],
            ['key' => 'tag__and', 'label' => 'Tag And', 'type' => 'text', 'default' => '', 'list_type' => 'integer'],
            ['key' => 'tag_slug__and', 'label' => 'Tag Slug And', 'type' => 'text', 'default' => '', 'list_type' => 'string'],
            ['key' => 'tag_slug__in', 'label' => 'Tag Slug In', 'type' => 'text', 'default' => '', 'list_type' => 'string'],
            // post parameters
            ['key' => 'p', 'label' => 'Post ID', 'type' => 'text', 'default' => ''],
            ['key' => 'name', 'label' => 'Post Name', 'type' => 'text', 'default' => ''],
            ['key' => 'post__in', 'label' => 'Post In', 'type' => 'text', 'default' => '', 'list_type' => 'integer'],
            ['key' => 'post__not_in', 'label' => 'Post Not In', 'type' => 'text', 'default' => '', 'list_type' => 'integer'],
            ['key' => 'post_parent', 'label' => 'Post Parent', 'type' => 'text', 'default' => ''],
            ['key' => 'post_parent__in', 'label' => 'Post Parent In', 'type' => 'text', 'default' => '', 'list_type' => 'integer'],
            ['key' => 'post_parent__not_in', 'label' => 'Post Parent Not In', 'type' => 'text', 'default' => '', 'list_type' => 'integer'],
            ['key' => 'post_name__in', 'label' => 'Post Name In', 'type' => 'text', 'default' => '', 'list_type' => 'string'],
            ['key' => 'post_type', 'label' => 'Post Type', 'type' => 'text', 'default' => '', 'list_type' => 'string'],
        ];
    }    

    protected static function get_default_groups()
    {
        return array(
            array(
                '__type' => 'posts_per_page',
                'parameter' => 'posts_per_page',
                'value' => 3
            ),
        );
    }
}
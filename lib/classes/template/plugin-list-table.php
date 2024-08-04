<?php namespace BuhlLib\Classes\Template;

defined( 'ABSPATH' ) || exit;

if (!class_exists('WP_List_Table')) {
	require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class PluginListTable extends \WP_List_Table
{

	protected $columns = array();
	protected $sortableColumns = array();
	protected $hiddenColumns = array();
	protected $title = '';
	protected $itemsPerPage = 20;
	protected $tableData = array();
	protected $defaultColumn = '';
	protected $orderby = 'asc';
	protected $filterDataOutput = '';

    protected $actions = [];

	/**
	 * PluginListTable constructor.
	 * @param $title
	 * @param $filterDataOutput
	 * @param array $tableData
	 * @param array $columns
	 * @param array $sortableColumns
	 */
	public function __construct($title, $filterDataOutput, $tableData = array(), $columns = array(), $sortableColumns = array())
	{
		parent::__construct();

		$this->setTitle($title);
		$this->setFilterDataOutput($filterDataOutput);
		$this->setColumns($columns);
		$this->setSortableColumns($sortableColumns);
		$this->setTableData($tableData);
		$this->prepare_items();
	}

	private function setFilterDataOutput($filter)
	{
		$this->filterDataOutput = $filter . '_column_default';
	}

	private function getFilteDataOutput()
	{
		return $this->filterDataOutput;
	}


	/**
	 * Prepare the items for the table to process
	 *
	 * @return Void
	 */
	public function prepare_items()
	{
		$columns = $this->get_columns();
		$hidden = $this->get_hidden_columns();
		$sortable = $this->get_sortable_columns();
		$data = $this->table_data();
		usort($data, array(&$this, 'sort_data'));
		$perPage = $this->getItemsPerPage();
		$currentPage = $this->get_pagenum();
		$totalItems = count($data);
		$this->set_pagination_args(array(
			'total_items' => $totalItems,
			'per_page' => $perPage
		));
		$data = array_slice($data, (($currentPage - 1) * $perPage), $perPage);
		$this->_column_headers = array($columns, $hidden, $sortable);
		$this->items = $data;
	}

	public function setOrderBy($orderby)
	{
		$this->orderby = $orderby;
	}

	public function setTableData($data = array())
	{
		$json = wp_json_encode($data);
		$this->tableData = json_decode($json, true);
	}

	public function setDefaultColumn($column)
	{
		$this->defaultColumn = $column;
	}

	public function getDefaultColumn()
	{
		return $this->defaultColumn;
	}

	public function getTableData()
	{
		return $this->tableData;
	}

	public function setItemsPerPage($number)
	{
		$this->itemsPerPage = $number;
	}

	public function getItemsPerPage()
	{
		return $this->itemsPerPage;
	}

	public function setColumns($columns = array())
	{
		$_columns = array();

		foreach ($columns AS $key => $column) {
			$_columns[$key] = $column['column'];

			if (array_key_exists('default', $column) && $column['default']) {
				$this->setDefaultColumn($key);

				if (array_key_exists('orderby', $column)) {
					$this->setOrderBy($column['orderby']);
				}
			}

            if (array_key_exists('actions', $column) && $column['actions']) {
                $this->actions[strtolower($column['column'])] = $column['actions'];
            }
		}

		$this->columns = $_columns;
	}

	public function setSortableColumns($sortable_columns = array())
	{
		$columns = array();

		foreach ($sortable_columns AS $key => $sortable_column) {
			$columns[$sortable_column] = array($sortable_column, false);
		}

		$this->sortableColumns = $columns;
	}

	public function setHiddenColumns($hidden_columns = array())
	{
		$this->hiddenColumns = $hidden_columns;
	}

	public function setTitle($title)
	{
		$this->title = $title;
	}

	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * Override the parent columns method. Display the table
	 */
	public function display()
	{
		echo '<style>#wpbody-content{width: 99%;}</style>';

		parent::display();
	}

	/**
	 * Override the parent columns method. Defines the columns to use in your listing table
	 *
	 * @return array
	 */
	public function get_columns()
	{
		return $this->columns;
	}

	/**
	 * Define which columns are hidden
	 *
	 * @return array
	 */
	public function get_hidden_columns()
	{
		return $this->hiddenColumns;
	}

	/**
	 * Define the sortable columns
	 *
	 * @return array
	 */
	public function get_sortable_columns()
	{
		return $this->sortableColumns;
	}

	/**
	 * Get the table data
	 *
	 * @return array
	 */
	private function table_data()
	{
		return $this->getTableData();
	}

	/**
	 * Define what data to show on each column of the table
	 *
	 * @param  Array $item Data
	 * @param  String $column_name - Current column name
	 *
	 * @return Mixed
	 */
	public function column_default($item, $column_name)
	{
		$value = apply_filters($this->getFilteDataOutput(), '', $item, $column_name);
        $_actions = [];

        if(!empty($this->actions[$column_name])) {
            $actions = $this->actions[$column_name];

            foreach($actions AS $action) {
                $replaces[] = $item[$action['specifier']];

                if(!empty($action['nonce']) && !empty($action['nonce_action'])) {
                    $replaces[] = wp_create_nonce($action['nonce_action']);
                }

                $_actions[$action['action_name']] = sprintf('<a href="%s">%s</a>', vsprintf($action['endpoint'], $replaces), $action['label']);
            }
        }

        if(empty($value)) {
            if (is_object($item)) {
                $value = $item->{$column_name};
            } elseif(is_array($item)) {
                $value = $item[$column_name];
            }
        }

        return sprintf('%1$s %2$s', $value, $this->row_actions($_actions));
	}

	public function getOrderBy()
	{
		return $this->orderby;
	}

	/**
	 * Allows you to sort the data by the variables set in the $_GET
	 *
	 * @return Mixed
	 */
	private function sort_data($a, $b)
	{
		// Set defaults
		$orderby = $this->getDefaultColumn();
		$order = $this->getOrderBy();
		// If orderby is set, use this as the sort column
		if (!empty($_GET['orderby'])) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$orderby = wc_clean($_GET['orderby']); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}
		// If order is set use this as the order
		if (!empty($_GET['order'])) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$order = wc_clean($_GET['order']); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}
		$result = strcmp($a[$orderby], $b[$orderby]);
		if ($order === 'asc') {
			return $result;
		}
		return -$result;
	}

}
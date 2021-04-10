<?php

namespace App\Grids;

use Closure;
use Leantony\Grid\Grid;

class MessageTypeGrid extends Grid implements MessageTypeGridInterface
{
    /**
     * The name of the grid
     *
     * @var string
     */
    protected $name = 'MessageType';

    public function getGridPaginationPageSize(): int
    {
        return 5;
    } 

    /**
     * List of buttons to be generated on the grid
     *
     * @var array
     */
    protected $buttonsToGenerate = [
        //'create',
        //'view',
        //'delete',
        //'refresh',
        //'export'
    ];

    /**
     * Specify if the rows on the table should be clicked to navigate to the record
     *
     * @var bool
     */
    protected $linkableRows = false;

    /**
    * Set the columns to be displayed.
    *
    * @return void
    * @throws \Exception if an error occurs during parsing of the data
    */
    public function setColumns()
    {
        $this->columns = [
		        "id" => [
  		        "label" => "ID",
  		        "filter" => [
  		            "enabled" => true,
  		            "operator" => "="
  		        ],
  		        "styles" => [
  		            "column" => "grid-w-10"
  		        ]
            ],
            "title" => [
                "data" => function($gridItem, $columnName) {
                    // $gridItem - column object
                    // $columnName - the name of this column (ie, name)
                    return $gridItem->title;
                },
                "search" => [
		            "enabled" => true
		        ],
		        "filter" => [
		            "enabled" => true,
		            "operator" => "like"
		        ]

            ],
            "color" => [
                "data" => function($gridItem, $columnName) {
                    // $gridItem - column object
                    // $columnName - the name of this column (ie, name)
                    return $gridItem->color;
                },
                "search" => [
		            "enabled" => true
		        ],
		        "filter" => [
		            "enabled" => true,
		            "operator" => "like"
		        ]

            ],
            "font_color" => [
                "data" => function($gridItem, $columnName) {
                    // $gridItem - column object
                    // $columnName - the name of this column (ie, name)
                    return $gridItem->font_color;
                },
                "search" => [
		            "enabled" => true
		        ],
		        "filter" => [
		            "enabled" => true,
		            "operator" => "like"
		        ]
            ],
            "font_size" => [
                "data" => function($gridItem, $columnName) {
                    // $gridItem - column object
                    // $columnName - the name of this column (ie, name)
                    return $gridItem->font_size;
                },
                "search" => [
		            "enabled" => true
		        ],
		        "filter" => [
		            "enabled" => true,
		            "operator" => "like"
		        ]
            ],
		    "custom" => [
                "raw" => true,
                "data" => function($gridItem, $columnName) {return '<a onclick="delItem('.$gridItem->id.')" href="#">Del</a>  <a onclick="editItem('.$gridItem->id.')" href="#">Edit</a>';},
		    ],
		];
    }

    /**
     * Set the links/routes. This are referenced using named routes, for the sake of simplicity
     *
     * @return void
     */
    public function setRoutes()
    {
        // searching, sorting and filtering
        $this->setIndexRouteName('admin.message_type.index');

        // crud support
        $this->setCreateRouteName('users.create');
        $this->setViewRouteName('users.show');
        $this->setDeleteRouteName('admin.users.index');

        // default route parameter
        $this->setDefaultRouteParameter('id');
    }

    /**
    * Return a closure that is executed per row, to render a link that will be clicked on to execute an action
    *
    * @return Closure
    */
    public function getLinkableCallback(): Closure
    {
        return function ($gridName, $item) {
            return route($this->getViewRouteName(), [$gridName => $item->id]);
        };
    }

    /**
    * Configure rendered buttons, or add your own
    *
    * @return void
    */
    public function configureButtons()
    {
        // call `addRowButton` to add a row button
        // call `addToolbarButton` to add a toolbar button
        // call `makeCustomButton` to do either of the above, but passing in the button properties as an array

        // call `editToolbarButton` to edit a toolbar button
        // call `editRowButton` to edit a row button
        // call `editButtonProperties` to do either of the above. All the edit functions accept the properties as an array
    }

    /**
    * Returns a closure that will be executed to apply a class for each row on the grid
    * The closure takes two arguments - `name` of grid, and `item` being iterated upon
    *
    * @return Closure
    */
    public function getRowCssStyle(): Closure
    {
        return function ($gridName, $item) {
            // e.g, to add a success class to specific table rows;
            // return $item->id % 2 === 0 ? 'table-success' : '';
            return "";
        };
    }
}
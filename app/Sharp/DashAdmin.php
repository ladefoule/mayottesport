<?php

namespace App\Sharp;

use Code16\Sharp\Dashboard\SharpDashboard;
use Code16\Sharp\Dashboard\DashboardQueryParams;
use Code16\Sharp\Dashboard\Widgets\SharpPanelWidget;
use Code16\Sharp\Dashboard\Layout\DashboardLayoutRow;
use Code16\Sharp\Dashboard\Widgets\SharpLineGraphWidget;

class DashAdmin extends SharpDashboard
{
    /**
     * Build dashboard's widget using ->addWidget.
     */
    protected function buildWidgets(): void
    {
        $this->addWidget(
            SharpLineGraphWidget::make("capacities")
                ->setTitle("Spaceships by capacity")

        )->addWidget(
            SharpPanelWidget::make("activeSpaceships")
                ->setInlineTemplate("<h1>{{count}}</h1> spaceships in activity")
                ->setLink('spaceship')
        );
    }

    /**
     * Build dashboard's widgets layout.
     */
    protected function buildWidgetsLayout(): void
    {
        $this->addFullWidthWidget("capacities")
        ->addRow(function(DashboardLayoutRow $row) {
            $row->addWidget(6, "activeSpaceships")
                ->addWidget(6, "inactiveSpaceships");
        });
    }

    /**
     * Build dashboard's widgets data, using ->addGraphDataSet and ->setPanelData
     *
     * @param DashboardQueryParams $params
     */
    protected function buildWidgetsData(DashboardQueryParams $params): void
    {
        $this->setOrderedListData(
            "topTravelledShipTypes", [
                [
                    "label" => "model EF5978",
                    "count" => 89
                ],
                [
                    "label" => "model TT4448",
                ],
                [
                    "label" => "model EF5978",
                    "count" => 17
                ],
                [
                    "label" => "model YY5557"
                ]
            ]
        );
    }
}

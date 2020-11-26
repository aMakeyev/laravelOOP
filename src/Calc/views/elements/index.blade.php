@section('content')
<h1>
    <span class="glyphicon glyphicon-list"></span> @lang('calc::titles.elements')
</h1>

<div id="elements" ng-app="Elements">
    <div ng-controller="ElementsCtrl as elem">

        <div class="row" style="margin-bottom: 20px">
            <div class="col-md-12">
                <button class="btn btn-sm btn-success" ng-click="elem.save()">Сохранить изменения</button>
            </div>
        </div>

        <div ui-tree="elem.treeOptions" id="categories" ng-cloak="true">
            <div class="categories" ui-tree-nodes ng-model="elem.tree" data-max-depth="1">
                <div class="category-block" ng-repeat="category in elem.tree" ui-tree-node ng-include="'node_category.html'"></div>
            </div>
        </div>

        <div style="margin-top: 10px;clear: both">
            <button class="btn btn-sm btn-success" ng-click="elem.addCategory()"><span class="glyphicon glyphicon-plus"></span> Добавить категорию</button>
        </div>
    </div>

    {{-- Шаблоны узлов дерева --}}

    {{-- Шаблон категории --}}
    <script type="text/ng-template" id="node_category.html">
        <div class="category-tree-node">
            <div class="category-node-title">
                <div ng-show="!category.editing" class="category-row">
                    <a title="Добавить элемент" class="btn btn-xs" ng-click="elem.addElement(category)"><span class="glyphicon glyphicon-plus"></span></a>
                    <span ui-tree-handle ng-bind="category.title"></span>
                    <span class="separator">&bull;</span>
                    <a class="btn btn-xs" ng-click="elem.nodeEdit(category)"><span class="glyphicon glyphicon-pencil"></span></a>
                    <a style="color:red" class="btn btn-xs" ng-click="elem.deleteCategory(category)"><span class="glyphicon glyphicon-remove"></span></a>
                </div>
                <div ng-show="category.editing" ng-include="'node_category_edit.html'"></div>
            </div>
        </div>
        <div class="category-node" ui-tree-nodes ng-model="category.elements">
            <div ng-repeat="element in category.elements" ui-tree-node ng-include="'node_element.html'"></div>
        </div>
    </script>

    {{-- Шаблон редактирования категории --}}
    <script type="text/ng-template" id="node_category_edit.html">
        <div class="node-editing node-editing-category">
            <input type="text" ng-model="category.title" ng-enter="elem.saveNode(category)" ng-esc="elem.cancelEditNode(category)"/>
            <label class="node_type"><input type="radio" ng-model="category.type" value="1"/> Фасад</label>
            <label class="node_type"><input type="radio" ng-model="category.type" value="2"/> Каркас</label>
            <label class="node_type"><input type="radio" ng-model="category.type" value="3"/> Фурнитура</label>
            <a class="btn btn-xs" ng-click="elem.saveNode(category)"><span class="glyphicon glyphicon-ok"></span></a>
            <a style="color:red" class="btn btn-xs" ng-click="elem.cancelEditNode(category)"><span class="glyphicon glyphicon-ban-circle"></span></a>
        </div>
    </script>

    {{-- Шаблон элемента --}}
    <script type="text/ng-template" id="node_element.html">
        <div class="element-node">
            <div class="element-node-title">
                <div ng-show="!element.editing">
                    <span class="drag-handler" ui-tree-handle><span class="glyphicon glyphicon-align-justify"></span></span>
                    <span ng-bind="element.title"></span>
                    <span class="separator">&bull;</span>
                    <a class="btn btn-xs" ng-click="elem.nodeEdit(element)"><span class="glyphicon glyphicon-pencil"></span></a>
                    <a style="color:red" class="btn btn-xs" ng-click="elem.deleteElement(category, element)"><span class="glyphicon glyphicon-remove"></span></a>
                </div>
                <div ng-show="element.editing" ng-include="'node_element_edit.html'"></div>
            </div>
        </div>
    </script>

    {{-- Шаблон редактирования элемента --}}
    <script type="text/ng-template" id="node_element_edit.html">
        <div class="node-editing node-editing-element">
            <input type="text" ng-model="element.title" ng-enter="elem.saveNode(element)" ng-esc="elem.cancelEditNode(element, category)" />
            <a class="btn btn-xs" ng-click="elem.saveNode(element)"><span class="glyphicon glyphicon-ok"></span></a>
            <a style="color:red" class="btn btn-xs" ng-click="elem.cancelEditNode(element, category)"><span class="glyphicon glyphicon-ban-circle"></span></a>
        </div>
    </script>
</div>
@stop

@section('scripts')
<script src="/static/vendor/angular/angular.min.js"></script>
<script src="/static/vendor/angular/angular-ui-tree.min.js"></script>
<script src="/static/js/app/elements/app.js"></script>
<script src="/static/js/app/elements/controller.js"></script>
<script src="/static/js/app/elements/TreeService.js"></script>
<script src="/static/js/app/services/NotifyService.js"></script>
@stop

@section('styles')
<link rel="stylesheet" href="/static/vendor/angular/angular-ui-tree.min.css"/>
@stop

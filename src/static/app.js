-(function () {
    var spiderBlockApp = angular.module('spiderBlockApp', []);

    spiderBlockApp.directive('jsonText', function () {
        return {
            restrict: 'A',
            require: 'ngModel',
            link: function (scope, element, attr, ngModel) {
                function into(input) {
                    return angular.fromJson(input);
                }

                function out(data) {
                    return angular.toJson(data, true);
                }

                ngModel.$parsers.push(into);
                ngModel.$formatters.push(out);
            }
        };
    });
    spiderBlockApp.controller('NotificationsCtrl', function ($scope, $rootScope, $timeout) {
        $scope.notifications = [];

        $rootScope.$on('notification', function (event, data) {
            $scope.notifications.push(data);
            $timeout(function () {
                $scope.removeNotification(data);
            }, 3000);
        });

        $scope.removeNotification = function (notification) {
            var index;
            if ($scope.notifications !== undefined) {
                index = $scope.notifications.indexOf(notification);
                $scope.notifications.splice(index, 1);
            }
        }
    });
    spiderBlockApp.controller('BotListCtrl', function ($scope, $http, $rootScope) {
        var wp_ajax = function (_req) {
            _req.nonce = window.sb_nonce;
            return $http({
                method: 'POST',
                url: ajaxurl,
                data: jQuery.param(_req),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            })
        };

        var find_bot = function (re) {
            for (var i = $scope.bots.length - 1; i >= 0; i--) {
                if ($scope.bots[i]['re'] == re) {
                    return i;
                }
            }
            return null;
        };

        $scope.bot = {"state": true};

        wp_ajax({
            action: 'NSB-get_list'
        }).success(function (res) {
            $scope.bots = res.data;
        });

        $scope.save = function () {
            wp_ajax({
                action: 'NSB-set_list',
                data: angular.toJson($scope.bots)
            }).success(function (res) {
                if (res.success) {
                    $scope.bots = res.data;
                    $rootScope.$emit('notification', {
                        state: 'success',
                        msg: 'List of bots was saved and new blocklist applied!'
                    });
                } else {
                    $rootScope.$emit('notification', {state: 'errror', msg: res.data});
                }
            });
        };

        $scope.reset = function () {
            wp_ajax({
                action: 'NSB-reset_list'
            }).success(function (res) {
                $scope.bots = res.data;
                $rootScope.$emit('notification', {
                    state: 'success',
                    msg: 'List of bots was reset to defaults!'
                });
            });
        };

        $scope.add = function () {
            $scope.bots.push($scope.bot);
            $rootScope.$emit('notification', {
                state: 'success',
                msg: 'Bot ' + $scope.bot.name + ' was added!'
            });
            $scope.bot = {"state": true};
        };

        $scope.remove = function (at) {
            $rootScope.$emit('notification', {state: 'success', msg: 'Bot was removed!'});
            $scope.bots.splice(find_bot(at), 1);
        };
    });
})(angular, document, jQuery);
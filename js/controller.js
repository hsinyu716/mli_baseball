/*
 * File:        controller.js
 * Maintainer:  Hsinyu Chen(z2493225@gmail.com) 
 * 
 */
var app = angular.module("myApp", []).directive('numbersOnly', function() {
	return {
		require: 'ngModel',
		link: function(scope, element, attrs, modelCtrl) {
			modelCtrl.$parsers.push(function(inputValue) {
				// this next if is necessary for when using ng-required on your input. 
				// In such cases, when a letter is typed first, this parser will be called
				// again, and the 2nd time, the value will be undefined
				if (inputValue == undefined) return ''
				var transformedInput = inputValue.replace(/[^0-9]/g, '');
				if (transformedInput != inputValue) {
					modelCtrl.$setViewValue(transformedInput);
					modelCtrl.$render();
				}

				return transformedInput;
			});
		}
	};
});;

app.controller("selectController", function($scope, $http) {
	$scope.confirm = function(o) {
		_show($('#loading'));
		if (o == 0) {
			location.href = self_;
		} else if (o == 1) {
			$('#fri_form').submit();
		}
	}
});

app.controller("friendController", function($scope, $http) {
	$scope.posi = posi_class;

	$scope.select = function(index) {
		pit = index;

		// 隱藏已選
		$.each($('#fri_form > input'), function(i, v) {
			if (i != pit) {
				$('#' + $(this).val()).hide();
			} else if (i == pit) {
				$('#' + $(this).val()).show();
			}
		});

		_show($('#friend_div2'));
	}

	$scope.selectf = function(o) {
		if ($('#' + o).hasClass('selected')) {
			$('#' + o).removeClass('selected');
			$('#fri_form > input').eq(pit).val(0);
		} else if (!$('#' + o).hasClass('selected')) {
			$('#' + $('#fri_form > input').eq(pit).val()).removeClass('selected');
			$('#' + o).addClass('selected');
			$('#fri_form > input').eq(pit).val(o);
			$('#fri' + pit).html('<img src="//graph.facebook.com/' + o + '/picture?width=60&height=60"/>');
		}
	}

	$scope.conf_sel = function() {
		_show($('#friend_div2'));
	}

	$scope.confirm = function(o) {
		var f = 0;
		$.each($('#fri_form > input'), function(i, v) {
			if ($(this).val() == 0 && f == 0) {
				show_toastr('toast-top-right', 'error', '請選擇' + posi_title[i], '');
				f = 1;
			}
		});
		if (f == 0) {
			_show($('#loading'));
			$('#fri_form').submit();
		}
	}
});

app.controller("resultController", function($scope, $http) {
	$scope.fusers = fuser;
	$scope.cpop = 0;
	$scope.view_ = function(o) {
		$scope.cpop = o;
		$scope.pop_pic = '//graph.facebook.com/' + fuser[o].uid + '/picture?width=159&height=159';
		$scope.pop_pic_name = fuser[o].name;
		$scope.pop_pic_role = 'img/pic-' + fuser[o].class + '.png';
		$scope.pop_cont_role = 'img/content-' + fuser[o].class + '.png';
		_show($('.bg'));
	}

	$scope.submit_ = function() {
		if (!checkform()) {
			return;
		}
		$.ajax({
			url: setDataurl,
			cache: false,
			type: 'post',
			data: $('#data_form').serialize(),
			dataType: 'json',
			beforeSend: function(html) {
				record('share_record');
				_show($('#loading'));
			},
			error: function(e) {
				//alert("error:"+e.responseText);
			},
			success: function(html) {
				_show($('#data_'));
				show_toastr('toast-top-right', 'success', '恭喜參加抽獎！', '');
			},
			complete: function() {
				_show($('#loading'));
				$('.btn4').hide();
				$('.btn5').show();
			}
		});
	}

	$scope.index = function() {
		location.href = indexurl;
	}

	$scope.share = function() {
		_show($('#loading'));
		$http({
			method: 'POST',
			url: shareurl,
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
			},
			data: "type=share&class=" + $scope.fusers[$scope.cpop].class + "&uid=" + $scope.fusers[$scope.cpop].uid + "&uname=" + $scope.fusers[$scope.cpop].name
		}).success(function(data) {
			_show($('#loading'));
			show_toastr('toast-top-right', 'success', '分享成功！', '');
		});
	}

	$scope.powall = function() {
		if (confirm('快告訴大家你的超完美強棒應援團，和他們一起贏得人生中重要的比賽吧！')) {
			_show($('#loading'));
			$http({
				method: 'POST',
				url: posturl,
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
				},
				data: "type=share"
			}).success(function(data) {
				_show($('#loading'));
				if (count == 0) {
					_show($('#data_'));
				}
			});
		}
	}

	$scope.checktrigger = function() {
		if (!$('#agree').prop('checked')) {
			$('#agree').prop('checked', true);
			$('.check').css({
				'background': 'url(img/mbtn3.png) no-repeat'
			});
		} else {
			$('#agree').prop('checked', false);
			$('.check').css({
				'background': 'url(img/mbtn2.png) no-repeat'
			});
		}
	}
});


app.controller("msgController", function($scope, $http) {
	$scope.submit_ = function(o) {
		if ($('#message').val() == '') {
			//bootbox.alert('請輸入留言！');
			show_toastr('toast-top-right', 'error', '請輸入留言！', '');
			return;
		}
		_show($('#loading'));
		$http({
			method: 'POST',
			url: msgurl,
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
			},
			data: "fbid=" + o_fbid + "&tofbid=" + fbid + "&message=" + $('#message').val()
		}).success(function(data) {
			show_toastr('toast-top-right', 'success', '留言成功！', '');
			setTimeout(function() {
				location.href = resulturl;
			}, 3000);
		});
	}
});
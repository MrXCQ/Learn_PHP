//
//  ViewController.m
//  learnToCN
//
//  Created by zc on 2020/5/11.
//  Copyright © 2020 com.IMpBear. All rights reserved.
//

#import "ViewController.h"
#import <CoreLocation/CoreLocation.h>
//#import "WifiBlueToothHelper.h"
//#import "blueToothWiFiConfig.h"


@interface ViewController ()
//@property (nonatomic,strong) WifiBlueToothHelper *helper;
@property (nonatomic,strong) CLLocationManager *locationManager;

@end

@implementation ViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    // Do any additional setup after loading the view.
    
    self.view.backgroundColor = UIColor.whiteColor ;

    // 请求地理位置权限
    // [self getLocationPersssion];
}


//-(void)touchesBegan:(NSSet<UITouch *> *)touches withEvent:(UIEvent *)event{
//
//    self.helper = [[WifiBlueToothHelper alloc]initWithWifiSSID:@"IMpBear" Password:@"123456888" uid:@"1688"];
//
//       self.helper.blueConnectStatus = ^(ble_connect_status status) {
//           NSLog(@"蓝牙连接状态 ----  %tu",status) ;
//       };
//
//       self.helper.blueWiFiConnectStatus = ^(connect_status status) {
//
//           if (status == connect_Success) {
//               NSLog(@"配网成功") ;
//           }else{
//
//               if (status == connect_FailWrongPwd) {
//                   NSLog(@"配网失败 --- WiFi密码错误") ;
//               }else if (status == connect_FailNoSearchWiFi){
//                   NSLog(@"配网失败 ---- 未搜索到WiFi") ;
//               }else{
//                   NSLog(@"配网失败") ;
//               }
//           }
//       };
//}
//
//
//#pragma mark -- 兼容iOS 获取WiFi信息请求地理位置权限
//-(void)getLocationPersssion{
//
//    // 因为iOS13 系统隐私问题 在读取WiFi信息前需要用户去开启地理位置权限，否则无法配网
//    if (@available(iOS 13, *)) {
//
//        self.locationManager = [[CLLocationManager alloc] init];
//
//        if (CLLocationManager.authorizationStatus == kCLAuthorizationStatusAuthorizedWhenInUse) {
//            //开启了权限，直接搜索
//        } else if (CLLocationManager.authorizationStatus == kCLAuthorizationStatusDenied) {
//            //如果用户没给权限，则提示
//        } else {
//            //请求权限
//            [self.locationManager requestWhenInUseAuthorization];
//        }
//
//    } else {
//        //ios 12 不需要开启定位权限
//    }
//}

@end

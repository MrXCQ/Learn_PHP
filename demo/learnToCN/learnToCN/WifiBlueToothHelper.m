//
//  WifiBlueToothHelper.m
//  WiFiStory
//
//  Created by XXxxi on 2019/4/22.
//  Copyright © 2019年 IMpBear. All rights reserved.
//

#import "WifiBlueToothHelper.h"
#import "bleconfigLibrary.h"
#import "Util.h"
#import <CoreBluetooth/CoreBluetooth.h>

#define DEVICE_NAME @"WiFi_Client"
#define PRODUCT_NAME1 @"realtek_rpt"
#define PRODUCT_NAME2 @"Ameba"  //测试机器
#define BTCONF_SERVICE_UUID                     @"FF01"
#define BTCONF_BLECONFIG_UUID                   @"2A0D"
#define MAX_BUF_SIZE                (512)
#define READ_GROUP_UNIT     3

#define NSLog(FORMAT, ...) NSLog(@"LOG >> Function:%s Line:%d Content:%@\n", __FUNCTION__, __LINE__, [NSString stringWithFormat:FORMAT, ##__VA_ARGS__])


NSMutableArray *AP_Profile_queue;
NSString *filter_BT_device = DEVICE_NAME;
NSString *selectDevice = @"";
BOOL wlan2GEnabled = NO;
BOOL wlan5GEnabled = NO;
struct rtk_btconfig_ss_result ss_result_2G;
int ss_section[22];
Byte product_type = 0;
struct rtk_btconfig_bss_info targetAp;

BOOL isShowConnectList = NO;
BOOL can_toConnect    = NO;
BOOL isConnected      = NO;
BOOL can_toSiteSurvey = NO;
BOOL isConfiguring    = NO;
BOOL isWiFiConnecting = NO;
BOOL isHomeAP_exist = NO;
BOOL isHomeAP_check = YES;
BOOL isWiFiList_ready = NO;

static WifiBlueToothHelper *helper = nil;

@interface WifiBlueToothHelper()<CBCentralManagerDelegate,CBPeripheralDelegate>
{
    NSMutableArray *m_devlist;                              // BT device list
    NSMutableArray *m_APlist;                               // AP list
    struct rtk_btconfig_ss_result ss_result_2G;
    struct rtk_btconfig_ss_result ss_result_5G;
    NSString *searchSSID ;
    NSInteger searchWifiCount ;    // 搜索WiFi 次数
}

//中心设备管理器
@property(nonatomic,strong) CBCentralManager *_Nullable centralManager;

//用来记录被连接的外设
@property(nonatomic,strong) CBPeripheral *_Nullable peripheral;

//扫描到的外设
@property(nonatomic,strong) NSMutableArray *peripheralArray;

//定时器用于扫描设备
@property(nonatomic,weak) NSTimer *scanTimer;

//判断蓝牙是否打开
@property(nonatomic,assign) BOOL isTurnOn;

@property(nonatomic,copy)NSString *xxi_password,*xxi_userid ,*inputSSID;

@end

@implementation WifiBlueToothHelper{
    
    bleconfigLibrary *handleRequest; //库处理Wifi信息
    int stateNum; //数据发送的状态
    CBCharacteristic *cb_cha; //记录特征
    
}

-(NSMutableArray *)peripheralArray{
    
    if(!_peripheralArray){
        _peripheralArray = [[NSMutableArray alloc]init];
    }
    return _peripheralArray;
}

//初始化单例
+ (WifiBlueToothHelper *)shared{
    static dispatch_once_t onceToken;
    dispatch_once(&onceToken, ^{
        helper = [[self alloc]init];
    });
    return  helper;
}

//重写初始化方法
-(instancetype)initWithWifiPassword:(NSString *)wifiPassword uid:(NSString *)userid{
    
    if(self = [super init]){
        self.xxi_password = [NSString stringWithFormat:@"%@",wifiPassword];
        self.xxi_userid = [NSString stringWithFormat:@"%@",userid];
        [self initCenterManager];
        handleRequest = [bleconfigLibrary alloc];
    }
    return self;
}

-(instancetype)initWithWifiSSID:(NSString *)ssid Password:(NSString *)wifiPassword uid:(NSString *)userid{
    if (self = [super init]) {
        self.xxi_password = [NSString stringWithFormat:@"%@",wifiPassword];
        self.xxi_userid = [NSString stringWithFormat:@"%@",userid];
        self.inputSSID = [NSString stringWithFormat:@"%@",ssid];
        m_APlist = [NSMutableArray array] ;
        searchWifiCount = 0 ;
        [self initCenterManager];
        handleRequest = [bleconfigLibrary alloc];
    }
    return self ;
}


-(void)initCenterManager{
    
    self.centralManager = [[CBCentralManager alloc] initWithDelegate:self queue:nil options:@{CBCentralManagerOptionShowPowerAlertKey:@NO}];
    _peripheral = nil;
}

//搜索外设
- (void)scan_BT_peripheral
{
    searchSSID = [NSString string] ;
    
    //设置起始状态
    stateNum = STATE_CFG_IDLE;
    //移除数据源
    [self.peripheralArray removeAllObjects];
    //5秒扫描蓝牙定时器
    //    self.scanTimer = [NSTimer timerWithTimeInterval:5.0f target:self selector:@selector(StopscanBlueToothDevice) userInfo:nil repeats:NO];
    //    [[NSRunLoop currentRunLoop] addTimer:self.scanTimer forMode:NSRunLoopCommonModes];  一加上这一句的
    //开始扫描
    [_centralManager scanForPeripheralsWithServices:nil
                                            options:@{ CBCentralManagerScanOptionAllowDuplicatesKey : @YES }];
    NSLog(@"Scanning started");
}

//关闭蓝牙的扫描
-(void)StopscanBlueToothDevice{
    
    //结束扫描
    [_centralManager stopScan];
    
}

#pragma mark - Central Methods
- (void)centralManagerDidUpdateState:(CBCentralManager *)central
{
    
    switch (central.state){
        case CBCentralManagerStatePoweredOn:
            //手机蓝牙已打开，开始外设的扫描
            NSLog(@"CBCentralManagerStatePoweredOn");
            _isTurnOn = YES;
            [self scan_BT_peripheral];
            break;
        case CBCentralManagerStatePoweredOff:
            //手机蓝牙已关闭，弹框提示
            NSLog(@"CBCentralManagerStatePoweredOff");
            _isTurnOn = NO;
            break;
        case CBCentralManagerStateResetting:
            NSLog(@"CBCentralManagerStateResetting");
            break;
        case CBCentralManagerStateUnsupported:
            NSLog(@"CBCentralManagerStateUnsupported");
            break;
        case CBCentralManagerStateUnauthorized:
            NSLog(@"CBCentralManagerStateUnauthorized");
            break;
        case CBCentralManagerStateUnknown:
            NSLog(@"CBCentralManagerStateUnknown");
            break;
    }
    
    if (central.state != CBCentralManagerStatePoweredOn) {
        return;
    }
}

//发现外设
-(void)centralManager:(CBCentralManager *)central didDiscoverPeripheral:(CBPeripheral *)peripheral advertisementData:(NSDictionary<NSString *,id> *)advertisementData RSSI:(NSNumber *)RSSI{
    
    BOOL found = NO;
    //过滤掉重复的外设
    for (NSDictionary *dic in self.peripheralArray) {
        CBPeripheral *p = [dic objectForKey:@"peripheral"];
        if ([p.identifier isEqual:peripheral.identifier]) {
            found = YES;
            break;
        }
    }
    
    if(!found && peripheral.name){
        NSDictionary *dic = @{@"peripheral":peripheral,@"deviceName":peripheral.name,@"macAddress":[peripheral.identifier UUIDString]};
        [self.peripheralArray addObject:dic];
        
        if([peripheral.name containsString:PRODUCT_NAME2]){
            NSLog(@"已搜索到蓝牙");
            self.peripheral = peripheral;  //赋值操作
            [central connectPeripheral:peripheral options:nil];
            [central stopScan];
        }
    }
}

// 蓝牙连接状态
-(void)centralManager:(CBCentralManager *)central didConnectPeripheral:(CBPeripheral *)peripheral{
    //    [MBProgressHUD showSuccess:@"蓝牙连接成功"];
    NSLog(@"蓝牙连接成功");
    
    if (self.blueConnectStatus) {
        self.blueConnectStatus(ble_Connect_Success) ;
    }
    
    peripheral.delegate = self;
    //寻找服务
    [peripheral discoverServices:@[[CBUUID UUIDWithString:BTCONF_SERVICE_UUID]]];
    stateNum = STATE_CAPABILITY;
    
}

-(void)centralManager:(CBCentralManager *)central didFailToConnectPeripheral:(CBPeripheral *)peripheral error:(NSError *)error{
    
    NSLog(@"蓝牙连接失败------error:%@",[error description]);
    
    if (self.blueConnectStatus) {
        self.blueConnectStatus(ble_Connect_Fail) ;
    }
}

- (void)peripheral:(CBPeripheral *)peripheral didDiscoverServices:(NSError *)error
{
    if (error) {
        //NSLog(@"Error discovering services: %@\n",[error localizedDescription]);
        return;
    }
    
    NSLog(@"Target %u service discovered:%@\n",(unsigned int)(peripheral.services.count),peripheral.services);
    
    // Loop through the newly filled peripheral.services array, just in case there's more than one.
    for (CBService *service in peripheral.services) {
        //NSLog(@"Service found with UUID: %@\n",service.UUID);
        [peripheral discoverCharacteristics:@[[CBUUID UUIDWithString:BTCONF_BLECONFIG_UUID]] forService:service];
    }
}

- (void)peripheral:(CBPeripheral *)peripheral didDiscoverCharacteristicsForService:(CBService *)service error:(NSError *)error
{
    
    if (error) {
        NSLog(@"1Error discovering characteristics: %@\n",[error localizedDescription]);
        
        if (self.blueConnectStatus) {
            self.blueConnectStatus(ble_CharacterError) ;
        }
        return;
    }
    
    NSLog(@"===================================Discover chr start");
    // Again, we loop through the array, just in case.
    for (CBCharacteristic *chr in service.characteristics) {
        
        if(service.UUID == NULL || chr.UUID == NULL)
            continue;
        
        NSLog(@"Service UUID %@ read characteristics %@", service.UUID,chr.UUID.UUIDString);
        
        if([chr.UUID.UUIDString isEqualToString:BTCONF_BLECONFIG_UUID]){
            [peripheral readValueForCharacteristic:chr];
            cb_cha = chr;
        }
    }
    NSLog(@"===================================Discover chr end");
}

//蓝牙接收到数据
-(void)peripheral:(CBPeripheral *)peripheral didUpdateValueForCharacteristic:(CBCharacteristic *)characteristic error:(NSError *)error{
    
    BOOL ss_isOver = NO;
    int i=0;
    int len=0;
    int ss_sectionNum=0;
    int ss_max_section = 0;
    uint8_t val[MAX_BUF_SIZE] = {0};
    
    if (searchSSID.length) {
        stateNum = STATE_CONNECTION ;
    }
    
    NSData *valData = [NSData dataWithBytes:(void*)&val length:sizeof(val)];
    
    if (error) {
        NSLog(@"2Error(%@) discovering characteristics: %@\n",characteristic.UUID.UUIDString,[error localizedDescription]);
        if (self.blueConnectStatus) {
            self.blueConnectStatus(ble_UpdateValueError) ;
        }
        return;
    }
    
    const uint8_t *bytes = (const uint8_t*)[characteristic.value bytes];
    
    NSData *bleData = [NSData dataWithBytes:(void*)&bytes length:sizeof(bytes)];
    
    
    NSLog(@"\n\n蓝牙发送的数据 ======== %@\n\n",characteristic.value);

    
    NSInteger totalData = [characteristic.value length] / sizeof(uint8_t);
    
    if(totalData==0 || totalData==1 ){
        len =[handleRequest gen_cmd_capability:val];
        valData = [NSData dataWithBytes:(const void *)val length:len];
        [peripheral writeValue:valData forCharacteristic:characteristic type:CBCharacteristicWriteWithResponse];
        [peripheral readValueForCharacteristic:characteristic];
        stateNum = STATE_CAPABILITY;
    }
    else{ //recv length > 1
        i = 0;
        
        if(stateNum == STATE_CAPABILITY){// get capability
            
            if( !([handleRequest is_cmd_capability:bytes]) ){
                len =[handleRequest gen_cmd_capability:val];
                valData = [NSData dataWithBytes:(const void *)val length:len];
                [peripheral writeValue:valData forCharacteristic:characteristic type:CBCharacteristicWriteWithResponse];
                [peripheral readValueForCharacteristic:characteristic];
            }else{// it is capability cmd
                
                wlan2GEnabled = [handleRequest is_cmd_support_2G:bytes];
                wlan5GEnabled = [handleRequest is_cmd_support_5G:bytes];
                
                //NSLog(@"wlan2GEnabled: %d",wlan2GEnabled);
                //NSLog(@"wlan5GEnabled: %d",wlan5GEnabled);
                
                if(wlan2GEnabled == YES && wlan5GEnabled == NO){
                    NSLog(@"Support 2.4G Capability Only");
                    stateNum = STATE_SCAN_2G;
                    memset(&ss_result_2G,0,sizeof(ss_result_2G));
                    
                    len =[handleRequest gen_cmd_sitesurvey_2G:val];
                    
                    valData = [NSData dataWithBytes:(const void *)val length:len];
                    [peripheral writeValue:valData forCharacteristic:characteristic type:CBCharacteristicWriteWithResponse];
                    
                    memset(ss_section,0,sizeof(ss_section));
                    [peripheral readValueForCharacteristic:characteristic];
                }
                else if(wlan5GEnabled == YES && wlan2GEnabled == YES){
                    NSLog(@"Support Dual Band Capability");
                    stateNum = STATE_SCAN_2G;
                    memset(&ss_result_2G,0,sizeof(ss_result_2G));
                    
                    len =[handleRequest gen_cmd_sitesurvey_2G:val];
                    
                    valData = [NSData dataWithBytes:(const void *)val length:len];
                    [peripheral writeValue:valData forCharacteristic:characteristic type:CBCharacteristicWriteWithResponse];
                    
                    memset(ss_section,0,sizeof(ss_section));
                    [peripheral readValueForCharacteristic:characteristic];
                    
                }
                else if(wlan5GEnabled == YES && wlan2GEnabled == NO){
                    NSLog(@"Support 5G Capability Only");
                    stateNum = STATE_SCAN_5G;
                    memset(&ss_result_5G,0,sizeof(ss_result_5G));
                    
                    len =[handleRequest gen_cmd_sitesurvey_5G:val];
                    valData = [NSData dataWithBytes:(const void *)val length:len];
                    [peripheral writeValue:valData forCharacteristic:characteristic type:CBCharacteristicWriteWithResponse];
                    
                    memset(ss_section,0,sizeof(ss_section));
                    [peripheral readValueForCharacteristic:characteristic];
                }
                
                product_type = [handleRequest get_product_type:bytes];
                //NSLog(@"product_type:%d",product_type);
                
            }
        }
        else if(stateNum == STATE_SCAN_5G){
            //site survey 5G
            //NSLog(@"扫描WiFi ------  STATE_SCAN_5G") ;
            NSString *homeAP_BSSID = [Util fetchCurrBSSID];
            homeAP_BSSID = [Util standardFormateMAC:homeAP_BSSID];
            
            if([handleRequest is_cmd_ss_5G:bytes]){
                
                ss_max_section = RTK_BTCONFIG_MAX_BSS_NUM/READ_GROUP_UNIT;
                if(RTK_BTCONFIG_MAX_BSS_NUM%READ_GROUP_UNIT!=0)ss_max_section++;
                
                ss_sectionNum = bytes[6]/READ_GROUP_UNIT;
                if(bytes[6]%READ_GROUP_UNIT)ss_sectionNum++;
                ss_section[ss_sectionNum-1] = 1;
                //check it if finish
                ss_isOver = YES;
                for(i=0;i<ss_max_section;i++){
                    if(ss_section[i]==0)
                        ss_isOver=NO;
                }
                if( ss_isOver==NO && ss_sectionNum>0 && ss_sectionNum<=ss_max_section ){
                    [peripheral readValueForCharacteristic:characteristic];
                    
                    [handleRequest setSiteSurveyResult :bytes:&(ss_result_5G):ss_sectionNum:READ_GROUP_UNIT];
                    
                    for(int i=0;i<3;i++){
                        
                        char bssid_tmp[32] = {0};
                        [Util mac2str: (char *)ss_result_5G.bss_info[(ss_sectionNum-1)*3+i].bdBssId :bssid_tmp];
                        NSString* scan_BSSID = [NSString stringWithCString:bssid_tmp encoding:NSASCIIStringEncoding];
                        
                        if([scan_BSSID isEqualToString:homeAP_BSSID]){
                            
                            if(isHomeAP_check == YES){
                                isHomeAP_exist = YES;
                                isHomeAP_check = NO;
                                memset(&targetAp,0,sizeof(targetAp));
                                memcpy(&targetAp,&ss_result_5G.bss_info[(ss_sectionNum-1)*3+i],sizeof(targetAp));
                                stateNum = STATE_CONNECTION;
                                //[self alertView_checkPassword_HomeAP :ss_result_5G.bss_info[(ss_sectionNum-1)*3+i].authAlg];
                            }
                        }
                    }
                }else if(ss_isOver==YES){
                    
                    NSLog(@"当前5G 未连接网络需要扫描周围WiFi") ;
                    isWiFiList_ready = YES;
                    
                    char ssid[32] = {0};
                    int macTotalNum = 0;
                    
                    for(i=0;i<RTK_BTCONFIG_MAX_BSS_NUM;i++){
                        memcpy(ssid,ss_result_5G.bss_info[i].bdSsIdBuf,sizeof(ssid));
                        
                        macTotalNum = ss_result_5G.bss_info[i].bdBssId[0]+
                        ss_result_5G.bss_info[i].bdBssId[1]+
                        ss_result_5G.bss_info[i].bdBssId[2]+
                        ss_result_5G.bss_info[i].bdBssId[3]+
                        ss_result_5G.bss_info[i].bdBssId[4]+
                        ss_result_5G.bss_info[i].bdBssId[5];
                        
                        if(ss_result_5G.bss_info[i].rssi>0 && macTotalNum!=0 && ss_result_5G.bss_info[i].ChannelNumber>0){
                            
                            struct rtk_btconfig_bss_info bssInfo_test;
                            int j;
                            BOOL found = false;
                            NSValue *dev_val;
                            
                            for(j=0;j<m_APlist.count;j++){
                                dev_val = [m_APlist objectAtIndex:j];
                                [dev_val getValue:&bssInfo_test];
                                if((bssInfo_test.bdBssId[0] == ss_result_5G.bss_info[i].bdBssId[0])&&(bssInfo_test.bdBssId[1] == ss_result_5G.bss_info[i].bdBssId[1])&&(bssInfo_test.bdBssId[2] == ss_result_5G.bss_info[i].bdBssId[2])&&(bssInfo_test.bdBssId[3] == ss_result_5G.bss_info[i].bdBssId[3])&&(bssInfo_test.bdBssId[4] == ss_result_5G.bss_info[i].bdBssId[4])&&(bssInfo_test.bdBssId[5] == ss_result_5G.bss_info[i].bdBssId[5])){
                                    
                                    found = true;
                                    
                                    // update TTL and RSSI and security type for existed AP
                                    bssInfo_test.TTL = 5;
                                    bssInfo_test.rssi = ss_result_5G.bss_info[i].rssi;
                                    bssInfo_test.authAlg = ss_result_5G.bss_info[i].authAlg;
                                    
                                    [m_APlist replaceObjectAtIndex:j withObject:[NSValue valueWithBytes:&bssInfo_test objCType:@encode(struct rtk_btconfig_bss_info)]];
                                }
                            }
                            
                            if(found == false){
                                ss_result_5G.bss_info[i].TTL = 5;
                                [m_APlist addObject:[NSValue valueWithBytes:&ss_result_5G.bss_info[i] objCType:@encode(struct rtk_btconfig_bss_info)]];
                            }
                        }
                    }
                    
                    struct rtk_btconfig_bss_info bssInfo_test;
                    int j;
                    NSValue *dev_val;
                    
                    for(j=0;j<m_APlist.count;j++){
                        dev_val = [m_APlist objectAtIndex:j];
                        [dev_val getValue:&bssInfo_test];
                        bssInfo_test.TTL--;
                        [m_APlist replaceObjectAtIndex:j withObject:[NSValue valueWithBytes:&bssInfo_test objCType:@encode(struct rtk_btconfig_bss_info)]];
                        
                        if(bssInfo_test.TTL == 0){
                            [m_APlist removeObjectAtIndex:j];
                        }
                    }
                    
                    [self sortAP_byRSSI];
                    
                    NSValue *dev_val_tmp;
                    struct rtk_btconfig_bss_info bssInfo_test_tmp;
                    char ssid_tmp[32] = {0};
                    
                    //check dulicated SSID AP (remove low RSSI repeated SSID)
                    for(j=0;j<m_APlist.count;j++){
                        dev_val = [m_APlist objectAtIndex:j];
                        [dev_val getValue:&bssInfo_test];
                        memcpy(ssid,bssInfo_test.bdSsIdBuf,sizeof(ssid));
                        if(strlen(ssid) != 0){ //hidden SSID
                            for(i=j+1;i<m_APlist.count;i++){
                                dev_val_tmp = [m_APlist objectAtIndex:i];
                                [dev_val_tmp getValue:&bssInfo_test_tmp];
                                memcpy(ssid_tmp,bssInfo_test_tmp.bdSsIdBuf,sizeof(ssid_tmp));
                                if(strcmp(ssid, ssid_tmp) == 0){
                                    [m_APlist removeObjectAtIndex:i];
                                }
                            }
                        }
                    }
                    
                    for(j=0;j<m_APlist.count;j++){
                        dev_val = [m_APlist objectAtIndex:j];
                        [dev_val getValue:&bssInfo_test];
                        memcpy(ssid,bssInfo_test.bdSsIdBuf,sizeof(ssid));
                    }
                    
                    
                    [peripheral readValueForCharacteristic:characteristic];
                    
                    stateNum = STATE_CAPABILITY;
                }
            }
            
        }
        else if(stateNum == STATE_SCAN_2G){
            //site survey 2.4G
            //NSLog(@"扫描WiFi ------  STATE_SCAN_2G") ;
            
            NSString *homeAP_BSSID = [Util fetchCurrBSSID];
            homeAP_BSSID = [Util standardFormateMAC:homeAP_BSSID];
            
            ss_max_section = RTK_BTCONFIG_MAX_BSS_NUM/READ_GROUP_UNIT;
            if(RTK_BTCONFIG_MAX_BSS_NUM%READ_GROUP_UNIT!=0)ss_max_section++;
            
            ss_sectionNum = bytes[6]/READ_GROUP_UNIT;
            if(bytes[6]%READ_GROUP_UNIT)ss_sectionNum++;
            
            ss_section[ss_sectionNum-1] = 1;
            //check it if finish
            ss_isOver = YES;
            for(i=0;i<ss_max_section;i++){
                if(ss_section[i]==0)
                    ss_isOver=NO;
            }
            if (searchSSID.length) {
                ss_isOver = YES ;
            }
            
            if( ss_isOver==NO && ss_sectionNum>0 && ss_sectionNum<=ss_max_section ){
                [peripheral readValueForCharacteristic:characteristic];
                
                [handleRequest setSiteSurveyResult :bytes:&(ss_result_2G):ss_sectionNum:READ_GROUP_UNIT];
                
                for(int i=0;i<3;i++){
                    char bssid_tmp[32] = {0};
                    [Util mac2str: (char *)ss_result_2G.bss_info[(ss_sectionNum-1)*3+i].bdBssId :bssid_tmp];
                    NSString* scan_BSSID = [NSString stringWithCString:bssid_tmp encoding:NSASCIIStringEncoding];
                    
                    if([scan_BSSID isEqualToString:homeAP_BSSID]){
                        
                        if(isHomeAP_check == YES){
                            isHomeAP_exist = YES;
                            isHomeAP_check = NO;
                            
                            memset(&targetAp,0,sizeof(targetAp));
                            memcpy(&targetAp,&ss_result_2G.bss_info[(ss_sectionNum-1)*3+i],sizeof(targetAp));
                            stateNum = STATE_CONNECTION;
                        }
                    }
                }
            }
            
            if(ss_isOver==YES){
                
                isWiFiList_ready = YES;
                
                char ssid[32] = {0};
                int macTotalNum = 0;
                
                for(i=0;i<RTK_BTCONFIG_MAX_BSS_NUM;i++){
                    memcpy(ssid,ss_result_2G.bss_info[i].bdSsIdBuf,sizeof(ssid));
                    
                    macTotalNum = ss_result_2G.bss_info[i].bdBssId[0]+
                    ss_result_2G.bss_info[i].bdBssId[1]+
                    ss_result_2G.bss_info[i].bdBssId[2]+
                    ss_result_2G.bss_info[i].bdBssId[3]+
                    ss_result_2G.bss_info[i].bdBssId[4]+
                    ss_result_2G.bss_info[i].bdBssId[5];
                    
                    if(ss_result_2G.bss_info[i].rssi>0 && macTotalNum!=0 && ss_result_2G.bss_info[i].ChannelNumber>0){
                        
                        struct rtk_btconfig_bss_info bssInfo_test;
                        int j;
                        BOOL found = false;
                        NSValue *dev_val;
                        
                        for(j=0;j<m_APlist.count;j++){
                            dev_val = [m_APlist objectAtIndex:j];
                            [dev_val getValue:&bssInfo_test];
                            if((bssInfo_test.bdBssId[0] == ss_result_2G.bss_info[i].bdBssId[0])&&(bssInfo_test.bdBssId[1] == ss_result_2G.bss_info[i].bdBssId[1])&&(bssInfo_test.bdBssId[2] == ss_result_2G.bss_info[i].bdBssId[2])&&(bssInfo_test.bdBssId[3] == ss_result_2G.bss_info[i].bdBssId[3])&&(bssInfo_test.bdBssId[4] == ss_result_2G.bss_info[i].bdBssId[4])&&(bssInfo_test.bdBssId[5] == ss_result_2G.bss_info[i].bdBssId[5])){
                                
                                found = true;
                                
                                // update TTL and RSSI for existed AP
                                bssInfo_test.TTL = 5;
                                bssInfo_test.rssi = ss_result_2G.bss_info[i].rssi;
                                bssInfo_test.authAlg = ss_result_2G.bss_info[i].authAlg;
                                
                                [m_APlist replaceObjectAtIndex:j withObject:[NSValue valueWithBytes:&bssInfo_test objCType:@encode(struct rtk_btconfig_bss_info)]];
                            }
                        }
                        
                        if(found == false){
                            ss_result_2G.bss_info[i].TTL = 5;
                            [m_APlist addObject:[NSValue valueWithBytes:&ss_result_2G.bss_info[i] objCType:@encode(struct rtk_btconfig_bss_info)]];
                        }
                    }
                }
                
                struct rtk_btconfig_bss_info bssInfo_test;
                int j;
                NSValue *dev_val;
                
                for(j=0;j<m_APlist.count;j++){
                    dev_val = [m_APlist objectAtIndex:j];
                    [dev_val getValue:&bssInfo_test];
                    bssInfo_test.TTL--;
                    [m_APlist replaceObjectAtIndex:j withObject:[NSValue valueWithBytes:&bssInfo_test objCType:@encode(struct rtk_btconfig_bss_info)]];
                    
                    if(bssInfo_test.TTL == 0){
                        [m_APlist removeObjectAtIndex:j];
                    }
                }
                
                [self sortAP_byRSSI];
                
                NSValue *dev_val_tmp;
                struct rtk_btconfig_bss_info bssInfo_test_tmp;
                char ssid_tmp[32] = {0};
                
                //check dulicated SSID AP (remove low RSSI repeated SSID)
                for(j=0;j<m_APlist.count;j++){
                    dev_val = [m_APlist objectAtIndex:j];
                    [dev_val getValue:&bssInfo_test];
                    memcpy(ssid,bssInfo_test.bdSsIdBuf,sizeof(ssid));
                    if(strlen(ssid) != 0){ //hidden SSID
                        for(i=j+1;i<m_APlist.count;i++){
                            dev_val_tmp = [m_APlist objectAtIndex:i];
                            [dev_val_tmp getValue:&bssInfo_test_tmp];
                            memcpy(ssid_tmp,bssInfo_test_tmp.bdSsIdBuf,sizeof(ssid_tmp));
                            if(strcmp(ssid, ssid_tmp) == 0){
                                [m_APlist removeObjectAtIndex:i];
                            }
                        }
                    }
                }
                
                for(j=0;j<m_APlist.count;j++){
                    dev_val = [m_APlist objectAtIndex:j];
                    [dev_val getValue:&bssInfo_test];
                    memcpy(ssid,bssInfo_test.bdSsIdBuf,sizeof(ssid));
                    
                }
                
                if(wlan5GEnabled == NO){
                    [peripheral readValueForCharacteristic:characteristic];
                    stateNum = STATE_CAPABILITY;
                }else{
                    stateNum = STATE_SCAN_25G;
                    memset(&ss_result_2G,0,sizeof(ss_result_2G));
                    
                    len =[handleRequest gen_cmd_sitesurvey_5G:val];
                    valData = [NSData dataWithBytes:(const void *)val length:len];
                    [peripheral writeValue:valData forCharacteristic:characteristic type:CBCharacteristicWriteWithResponse];
                    
                    memset(ss_section,0,sizeof(ss_section));
                    [peripheral readValueForCharacteristic:characteristic];
                }
                //}
            }
        }
        else if(stateNum == STATE_SCAN_25G){
            
            //NSLog(@"扫描WiFi ------  STATE_SCAN_25G") ;
            
            NSString *homeAP_BSSID = [Util fetchCurrBSSID];
            homeAP_BSSID = [Util standardFormateMAC:homeAP_BSSID];
            
            ss_max_section = RTK_BTCONFIG_MAX_BSS_NUM/READ_GROUP_UNIT;
            if(RTK_BTCONFIG_MAX_BSS_NUM%READ_GROUP_UNIT!=0)ss_max_section++;
            
            ss_sectionNum = bytes[6]/READ_GROUP_UNIT;
            if(bytes[6]%READ_GROUP_UNIT)ss_sectionNum++;
            
            ss_section[ss_sectionNum-1] = 1;
            //check it if finish
            ss_isOver = YES;
            for(i=0;i<ss_max_section;i++){
                if(ss_section[i]==0)
                    ss_isOver=NO;
            }
            
            if (searchSSID.length) {
                ss_isOver = YES ;
            }
            
            if( ss_isOver==NO && ss_sectionNum>0 && ss_sectionNum<=ss_max_section ){
                [peripheral readValueForCharacteristic:characteristic];
                
                [handleRequest setSiteSurveyResult :bytes:&(ss_result_2G):ss_sectionNum:READ_GROUP_UNIT];
                
                for(int i=0;i<3;i++){
                    char bssid_tmp[32] = {0};
                    [Util mac2str: (char *)ss_result_2G.bss_info[(ss_sectionNum-1)*3+i].bdBssId :bssid_tmp];
                    NSString* scan_BSSID = [NSString stringWithCString:bssid_tmp encoding:NSASCIIStringEncoding];
                    
                    if([scan_BSSID isEqualToString:homeAP_BSSID]){
                        
                        if(isHomeAP_check == YES){
                            isHomeAP_exist = YES;
                            isHomeAP_check = NO;
                            
                            memset(&targetAp,0,sizeof(targetAp));
                            memcpy(&targetAp,&ss_result_2G.bss_info[(ss_sectionNum-1)*3+i],sizeof(targetAp));
                            stateNum = STATE_CONNECTION;
                        }
                    }
                }
            }
            
            if(ss_isOver==YES){
                
                isWiFiList_ready = YES;
                
                char ssid[32] = {0};
                int macTotalNum = 0;
                
                for(i=0;i<RTK_BTCONFIG_MAX_BSS_NUM;i++){
                    memcpy(ssid,ss_result_2G.bss_info[i].bdSsIdBuf,sizeof(ssid));
                    
                    macTotalNum = ss_result_2G.bss_info[i].bdBssId[0]+
                    ss_result_2G.bss_info[i].bdBssId[1]+
                    ss_result_2G.bss_info[i].bdBssId[2]+
                    ss_result_2G.bss_info[i].bdBssId[3]+
                    ss_result_2G.bss_info[i].bdBssId[4]+
                    ss_result_2G.bss_info[i].bdBssId[5];
                    
                    if(ss_result_2G.bss_info[i].rssi>0 && macTotalNum!=0 && ss_result_2G.bss_info[i].ChannelNumber>0){
                        
                        struct rtk_btconfig_bss_info bssInfo_test;
                        int j;
                        BOOL found = false;
                        NSValue *dev_val;
                        
                        for(j=0;j<m_APlist.count;j++){
                            dev_val = [m_APlist objectAtIndex:j];
                            [dev_val getValue:&bssInfo_test];
                            if((bssInfo_test.bdBssId[0] == ss_result_2G.bss_info[i].bdBssId[0])&&(bssInfo_test.bdBssId[1] == ss_result_2G.bss_info[i].bdBssId[1])&&(bssInfo_test.bdBssId[2] == ss_result_2G.bss_info[i].bdBssId[2])&&(bssInfo_test.bdBssId[3] == ss_result_2G.bss_info[i].bdBssId[3])&&(bssInfo_test.bdBssId[4] == ss_result_2G.bss_info[i].bdBssId[4])&&(bssInfo_test.bdBssId[5] == ss_result_2G.bss_info[i].bdBssId[5])){
                                
                                found = true;
                                
                                // update TTL and RSSI for existed AP
                                bssInfo_test.TTL = 5;
                                bssInfo_test.rssi = ss_result_2G.bss_info[i].rssi;
                                bssInfo_test.authAlg = ss_result_2G.bss_info[i].authAlg;
                                
                                [m_APlist replaceObjectAtIndex:j withObject:[NSValue valueWithBytes:&bssInfo_test objCType:@encode(struct rtk_btconfig_bss_info)]];
                            }
                        }
                        
                        if(found == false){
                            ss_result_2G.bss_info[i].TTL = 5;
                            [m_APlist addObject:[NSValue valueWithBytes:&ss_result_2G.bss_info[i] objCType:@encode(struct rtk_btconfig_bss_info)]];
                        }
                    }
                }
                
                struct rtk_btconfig_bss_info bssInfo_test;
                int j;
                NSValue *dev_val;
                
                for(j=0;j<m_APlist.count;j++){
                    dev_val = [m_APlist objectAtIndex:j];
                    [dev_val getValue:&bssInfo_test];
                    bssInfo_test.TTL--;
                    [m_APlist replaceObjectAtIndex:j withObject:[NSValue valueWithBytes:&bssInfo_test objCType:@encode(struct rtk_btconfig_bss_info)]];
                    
                    if(bssInfo_test.TTL == 0){
                        [m_APlist removeObjectAtIndex:j];
                    }
                }
                
                [self sortAP_byRSSI];
                
                NSValue *dev_val_tmp;
                struct rtk_btconfig_bss_info bssInfo_test_tmp;
                char ssid_tmp[32] = {0};
                
                //check dulicated SSID AP (remove low RSSI repeated SSID)
                for(j=0;j<m_APlist.count;j++){
                    dev_val = [m_APlist objectAtIndex:j];
                    [dev_val getValue:&bssInfo_test];
                    memcpy(ssid,bssInfo_test.bdSsIdBuf,sizeof(ssid));
                    if(strlen(ssid) != 0){ //hidden SSID
                        for(i=j+1;i<m_APlist.count;i++){
                            dev_val_tmp = [m_APlist objectAtIndex:i];
                            [dev_val_tmp getValue:&bssInfo_test_tmp];
                            memcpy(ssid_tmp,bssInfo_test_tmp.bdSsIdBuf,sizeof(ssid_tmp));
                            if(strcmp(ssid, ssid_tmp) == 0){
                                [m_APlist removeObjectAtIndex:i];
                            }
                        }
                    }
                }
                
                for(j=0;j<m_APlist.count;j++){
                    dev_val = [m_APlist objectAtIndex:j];
                    [dev_val getValue:&bssInfo_test];
                    memcpy(ssid,bssInfo_test.bdSsIdBuf,sizeof(ssid));
                }
                
                
                [peripheral readValueForCharacteristic:characteristic];
                
                stateNum = STATE_CAPABILITY;
            }
        }
        
        else if(stateNum == STATE_CONNECTION){
            //发送密码
            if( !([handleRequest is_cmd_connection:bytes]) ){
                
                NSString *m_password = [NSString stringWithFormat:@"%@uid%@",self.xxi_password,self.xxi_userid];
                //                NSString *m_password = [NSString stringWithFormat:@"%@uid%@",_xxipassword,_user.ChompUser_Userid];//self.passwordTF.text
                NSLog(@"向蓝牙发送的数据 ssid --- %@, password --- %@, userid --- %@",self.inputSSID,self.xxi_password,self.xxi_userid);
                const char *str_password = [m_password cStringUsingEncoding:NSUTF8StringEncoding];
                //buff内部赋值操作
                len = [handleRequest gen_cmd_connection_request:val :targetAp.ChannelNumber :targetAp.authAlg :targetAp.bdSsIdBuf :targetAp.bdBssId :(uint8_t*)str_password :(int)m_password.length];
                valData = [NSData dataWithBytes:(const void *)val length:len];
                [peripheral writeValue:valData forCharacteristic:characteristic type:CBCharacteristicWriteWithResponse];
                searchSSID = @"";
                //发送完密码之后监听
                sleep(5);
                
                [peripheral readValueForCharacteristic:characteristic];
                
            }else{
                NSLog(@"查看蓝牙配网的状态");
                NSDictionary * wrapper = [NSDictionary dictionaryWithObjectsAndKeys:peripheral,@"obj1",characteristic,@"obj2", nil];
                [NSTimer scheduledTimerWithTimeInterval:1.0 target:self selector:@selector(btcfg_cmd_getStatus:) userInfo:wrapper repeats:NO];
                
                stateNum = STATE_CONNECTION_STATUS;
            }
        }
        else if(stateNum == STATE_CONNECTION_STATUS){
            
            if( !([handleRequest is_cmd_status:bytes]) ){
                
                if([handleRequest getDevice_connectedStatus:bytes]==STATE_WRONG_PASSWORD){
                    //密码错误不会对应这一个状态
                    NSLog(@"wrong password");
                    
                    if(self.blueWiFiConnectStatus){
                        self.blueWiFiConnectStatus(connect_FailWrongPwd);
                    }
                    stateNum = STATE_CAPABILITY;
                    
                }else if([handleRequest getDevice_connectedStatus:bytes]==STATE_IDLE){ //密码错误也是走到这里来。。。。。。真是渣渣！
                    
                    if(self.blueWiFiConnectStatus){
                        self.blueWiFiConnectStatus(connect_Fail);
                    }
                    
                    stateNum = STATE_CAPABILITY;
                    NSLog(@"Connection Fail!");
                }else{
                    
                    uint8_t status = [handleRequest getDevice_connectedStatus:bytes] ;
                    
                    NSLog(@"其他的情况 ----- %tu",status) ;
                }
                NSDictionary * wrapper = [NSDictionary dictionaryWithObjectsAndKeys:peripheral,@"obj1",characteristic,@"obj2", nil];
                [NSTimer scheduledTimerWithTimeInterval:1.0 target:self selector:@selector(btcfg_cmd_getStatus:) userInfo:wrapper repeats:NO];
                
            }else{
                
                NSLog(@"无法获取到蓝牙的CMD状态") ;
                
                stateNum = STATE_CFG_IDLE;  //这一步的操作又是重置到最原始的状态上面来
                [peripheral readValueForCharacteristic:characteristic];
            }
        }
        else{
            
            //获取蓝牙配网连接状态
            NSLog(@"获取到蓝牙配网的状态 ------- %tu",[handleRequest getDevice_connectedStatus:bytes]) ;
            if([handleRequest getDevice_connectedStatus:bytes] != STATE_CONNECTED){
                NSLog(@"configuration failed:disconnect!!");
                if(self.blueWiFiConnectStatus){
                    self.blueWiFiConnectStatus(connect_Fail);
                }
            }
            else{
                NSLog(@"configuration success:   connected!!");
                if(self.blueWiFiConnectStatus){
                    self.blueWiFiConnectStatus(connect_Success);
                }
            }
            
            if(self.peripheral){
                [self.centralManager cancelPeripheralConnection:self.peripheral];  //手动断开蓝牙
            }
        }
    }
}

- (void)peripheral:(CBPeripheral *)peripheral didWriteValueForCharacteristic:(CBCharacteristic *)characteristic error:(NSError *)error
{
        
    const uint8_t *bytes = (const uint8_t*)[characteristic.value bytes];
    
    NSData *bleData = [NSData dataWithBytes:(void*)&bytes length:sizeof(bytes)];
    
    
    NSLog(@"\n\n\nAPP 向蓝牙发送的数据 ======== %@\n\n\n",characteristic.value);
    
    if (error) {
        //NSLog(@"3Error(%@) discovering characteristics: %@\n",characteristic.UUID.UUIDString,[error localizedDescription]);
        return;
    }
}

/** The peripheral letting us know whether our subscribe/unsubscribe happened or not
 */
- (void)peripheral:(CBPeripheral *)peripheral didUpdateNotificationStateForCharacteristic:(CBCharacteristic *)characteristic error:(NSError *)error
{
    
    NSLog(@">>>>Notification began on %@", characteristic);
    if (error) {
        NSLog(@"Error changing notification state: %@\n",[error localizedDescription]);
    }
    
    // Notification has started
    if (characteristic.isNotifying) {
        NSLog(@"Notification began on %@", characteristic);
    }
    
    // Notification has stopped
    else {
        // so disconnect from the peripheral
        NSLog(@"Notification stopped on %@.  Disconnecting", characteristic);
        [_centralManager cancelPeripheralConnection:peripheral];
    }
    
    NSString *stringFromData = [[NSString alloc] initWithData:characteristic.value encoding:NSUTF8StringEncoding];
    NSLog(@"\n>>>>>>>>>>>>Received:%@",stringFromData);
}

/** Once the disconnection happens, we need to clean up our local copy of the peripheral
 */
- (void)centralManager:(CBCentralManager *)central didDisconnectPeripheral:(CBPeripheral *)peripheral error:(NSError *)error
{
    NSLog(@"Peripheral Disconnected to %@. (%@)\n", peripheral, [error localizedDescription]);
    
    if (self.blueConnectStatus) {
        self.blueConnectStatus(ble_Peripheral_disconnect) ;
    }
}



#pragma mark -- 按照RSSI 信号进行排序
-(void)sortAP_byRSSI
{
    int AP_count = (int)m_APlist.count;
    
    NSLog(@"整理 M_aplistcount ----- %d",AP_count) ;
    
    searchWifiCount ++ ;
    
    if (AP_count) {
        NSValue *dev_val ;
        struct rtk_btconfig_bss_info bssInfo ;
        
        for (int i = 0; i<AP_count; i++) {
            dev_val = [m_APlist objectAtIndex:i];
            [dev_val getValue:&bssInfo];
            
            NSLog(@"ssid --- %s,mac ---- %s",bssInfo.bdSsIdBuf,bssInfo.bdBssId) ;
            
            NSString * scanWifiSSID =  [[NSString alloc]initWithBytes:bssInfo.bdSsIdBuf length:sizeof(bssInfo.bdSsIdBuf) encoding:NSUTF8StringEncoding];
            NSString * scanWifiMAC = [[NSString alloc]initWithBytes:bssInfo.bdBssId length:sizeof(bssInfo.bdBssId) encoding:NSUTF8StringEncoding];
            
            NSString *tempStr = [NSString stringWithFormat:@"%@",scanWifiSSID] ;
            // 1. 去掉首尾空格和换行符
            tempStr = [tempStr stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceAndNewlineCharacterSet]];
            // 2. 去掉所有空格和换行符
            tempStr = [tempStr stringByReplacingOccurrencesOfString:@"\0" withString:@""];
            
            NSString *tempstr2 = [NSString stringWithFormat:@"%@",self.inputSSID] ;
            
            if ([tempStr containsString:@" "]) {
                tempStr = [tempStr stringByReplacingOccurrencesOfString:@" " withString:@""];
            }
            
            if ([tempstr2 containsString:@" "]) {
                tempstr2 = [tempstr2 stringByReplacingOccurrencesOfString:@" " withString:@""];
            }
            
            
            if ([tempStr isEqualToString:tempstr2]) {
                NSLog(@"scanssid ---- %@,mac --- %@",scanWifiSSID,scanWifiMAC) ;
                char ssid[32] = {0};
                memcpy(ssid,bssInfo.bdSsIdBuf,sizeof(ssid));
                memset(&targetAp,0,sizeof(targetAp));
                memcpy(&targetAp,&bssInfo,sizeof(targetAp));
                stateNum = STATE_CONNECTION ;
                searchSSID = self.inputSSID ;
                searchWifiCount = 0 ;
                NSLog(@"已经设置了 state——connection") ;
            }
        }
    }
    
    NSLog(@"搜索wifi 的次数 ----- %tu",searchWifiCount ) ;
    
    if (searchWifiCount >= 10) {
        
        NSLog(@"未搜索到对应的WiFi 请重试");
        
        searchWifiCount = 0  ;
        
        if (self.blueWiFiConnectStatus) {
            self.blueWiFiConnectStatus(connect_FailNoSearchWiFi) ;
        }
        
        [self blueTooth_dealloc];
        
        return ;
    }
    
    struct rtk_btconfig_bss_info AP_info[RTK_BTCONFIG_MAX_BSS_NUM*5] = {0};//2G+5G
    
    struct rtk_btconfig_bss_info tmp = {0};
    
    NSValue *dev_val;
    
    for(int i=0;i<AP_count;i++){
        
        dev_val = [m_APlist objectAtIndex:i];
        
        [dev_val getValue:&tmp];
        
        memcpy(&AP_info[i],&tmp,sizeof(struct rtk_btconfig_bss_info));
    }
    
    //sort
    memset(&tmp,0,sizeof(struct rtk_btconfig_bss_info));
    
    //    char c_bssid[32] = {0};
    //
    //    int counter = 0;
    //    int queue_count = (int)AP_Profile_queue.count;
    //
    //    for(j=queue_count;j>0;j--){
    //        for(i=0;i<AP_count-1;i++){
    //            [Util mac2str: (char *)AP_info[i].bdBssId :c_bssid];
    //            NSString *bssid = [NSString stringWithCString:c_bssid encoding:NSASCIIStringEncoding];
    //            if([bssid isEqualToString:[AP_Profile_queue objectAtIndex:j-1]]){
    //                memcpy(&tmp,&AP_info[i],sizeof(struct rtk_btconfig_bss_info));
    //                memcpy(&AP_info[i],&AP_info[counter],sizeof(struct rtk_btconfig_bss_info));
    //                memcpy(&AP_info[counter],&tmp,sizeof(struct rtk_btconfig_bss_info));
    //                counter ++;
    //            }
    //        }
    //    }
    //
    //    for(i=counter;i<AP_count-1;i++){
    //        for(j=counter;j<AP_count-1;j++){
    //            if(AP_info[j].rssi<AP_info[j+1].rssi){//swap
    //                memcpy(&tmp,&AP_info[j],sizeof(struct rtk_btconfig_bss_info));
    //                memcpy(&AP_info[j],&AP_info[j+1],sizeof(struct rtk_btconfig_bss_info));
    //                memcpy(&AP_info[j+1],&tmp,sizeof(struct rtk_btconfig_bss_info));
    //            }
    //        }
    //    }
    //
    [m_APlist removeAllObjects];
    
    for(int i=0;i<AP_count;i++){
        [m_APlist addObject:[NSValue valueWithBytes:&AP_info[i] objCType:@encode(struct rtk_btconfig_bss_info)]];
    }
}



//查看蓝牙配网状态
-(void)btcfg_cmd_getStatus:(NSTimer*)t
{
    int len = 0;
    NSDictionary *wrapper = (NSDictionary*)[t userInfo];
    CBPeripheral *peripheral = [wrapper objectForKey:@"obj1"];
    CBCharacteristic *characteristic = [wrapper objectForKey:@"obj2"];
    
    NSData *valData = nil;
    uint8_t val[MAX_BUF_SIZE] = {0};
    
    len = [handleRequest gen_cmd_connection_status:val];
    valData = [NSData dataWithBytes:(const void *)val length:5];
    
    [peripheral writeValue:valData forCharacteristic:characteristic type:CBCharacteristicWriteWithResponse];
    [peripheral readValueForCharacteristic:characteristic];
}

//蓝牙销毁
-(void)blueTooth_dealloc{
    
    //销毁掉中央和外设
    if(self.peripheral){
        [self.centralManager cancelPeripheralConnection:self.peripheral];
    }
    
    if(self.centralManager){
        self.centralManager = nil;
    }
    
}

@end

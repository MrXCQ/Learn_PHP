//
//  learnHanZiViewController.m
//  learnToCN
//
//  Created by zc on 2020/5/12.
//  Copyright © 2020 com.IMpBear. All rights reserved.
//

#import "learnHanZiViewController.h"


@interface XCQThemeButton : QMUIFillButton

@property(nonatomic,strong) UIColor *themeColor;
@property(nonatomic,copy) NSString *themeName ;

@end

@interface learnHanZiViewController ()
@property(nonatomic,strong) NSArray<Class> *themeClasses ;
@end

@implementation learnHanZiViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    // Do any additional setup after loading the view.
    
    [self configtQMUI];
 
    [self configUI];
    
}

-(void)configUI{
    
    self.title = @"HanZi";
    
    UIView *testView = [[UIView alloc]initWithFrame:CGRectMake(0, 0, 100, 100)];
    testView.center = self.view.center ;
    
    testView.backgroundColor = [UIColor qmui_colorWithThemeProvider:^UIColor * _Nonnull(__kindof QMUIThemeManager * _Nonnull manager, NSString * _Nullable identifier, NSObject<QDThemeProtocol> * _Nullable theme) {
        return [theme.themeTintColor colorWithAlphaComponent:.5];
    }];
    
    [self.view addSubview:testView];
    
    
    for (int i = 0; i<3; i++) {
        UIButton *clickBtn = [UIButton buttonWithType:UIButtonTypeCustom];
        
        clickBtn.backgroundColor = UIColor.qmui_randomColor ;
        
        clickBtn.tag = 100 + i ;
        
        clickBtn.frame = CGRectMake(0, 100 * i +50, 100, 40);
        
        [clickBtn addTarget:self action:@selector(click:) forControlEvents:UIControlEventTouchUpInside];
        
        [self.view addSubview:clickBtn];
        
    }
    
    
  
    
}


-(void)click:(UIButton *)btn{
 
    if (btn.tag == 100) {
         QMUIThemeManagerCenter.defaultThemeManager.currentThemeIdentifier = QDThemeIdentifierDark;

    }else if (btn.tag == 101){
        QMUIThemeManagerCenter.defaultThemeManager.currentThemeIdentifier = QDThemeIdentifierGrass;

    }else{
        QMUIThemeManagerCenter.defaultThemeManager.currentThemeIdentifier = QDThemeIdentifierDefault;

    }
}


-(void)configtQMUI{
    self.themeClasses = @[
         QMUIConfigurationTemplate.class,
         QMUIConfigurationTemplateGrapefruit.class,
         QMUIConfigurationTemplateGrass.class,
         QMUIConfigurationTemplatePinkRose.class,
         QMUIConfigurationTemplateDark.class];
     
     [self.themeClasses enumerateObjectsUsingBlock:^(Class  _Nonnull class, NSUInteger idx, BOOL * _Nonnull stop) {
         BOOL hasInstance = NO;
         for (NSObject<QDThemeProtocol> *theme in QMUIThemeManagerCenter.defaultThemeManager.themes) {
             if ([theme isKindOfClass:class]) {
                 hasInstance = YES;
                 break;
             }
         }
         
         if (!hasInstance) {
             NSObject<QDThemeProtocol> *theme = [class new];
             [QMUIThemeManagerCenter.defaultThemeManager addThemeIdentifier:theme.themeName theme:theme];
         }
     }];
}


-(void)didInitialize{
    [super didInitialize];
}









@end

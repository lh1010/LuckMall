-- ----------------------------
-- 表结构 `luck_administrator`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `luck_administrator` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(255) NOT NULL DEFAULT '' COMMENT '密码',
  `sex` tinyint(3) NOT NULL DEFAULT '3' COMMENT '性别 1男 2女 3未知',
  `phone` varchar(20) NOT NULL DEFAULT '',
  `email` varchar(32) NOT NULL DEFAULT '',
  `remark` varchar(255) NOT NULL DEFAULT '',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(3) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

-- ----------------------------
-- 表结构 `luck_administrator_login_log`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `luck_administrator_login_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `administrator_id` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(100) NOT NULL DEFAULT '',
  `browser` varchar(20) NOT NULL DEFAULT '',
  `browser_version` varchar(20) NOT NULL DEFAULT '',
  `token` varchar(100) NOT NULL DEFAULT '',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=352 DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

-- ----------------------------
-- 表结构 `luck_adver`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `luck_adver` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `client` varchar(25) NOT NULL DEFAULT 'wxapp' COMMENT '客户端 wxapp|pc',
  `type` varchar(20) NOT NULL DEFAULT '' COMMENT '类型 system=系统使用',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(3) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- 转存表中的数据 luck_adver
-- ----------------------------
INSERT INTO `luck_adver` VALUES ('1', '首页轮播图', '微信小程序客户端，首页顶部轮播图。', 'wxapp', 'system', '2020-08-06 12:07:04', '1');
INSERT INTO `luck_adver` VALUES ('2', '首页轮播图', '', 'pc', 'system', '2020-08-06 12:09:36', '1');
INSERT INTO `luck_adver` VALUES ('3', '首页热销推荐', '首页热销推荐', 'pc', 'system', '2020-09-15 14:25:50', '1');
INSERT INTO `luck_adver` VALUES ('4', '首页热销推荐下广告', '', 'pc', 'system', '2020-09-15 15:02:58', '1');
INSERT INTO `luck_adver` VALUES ('5', '登录页广告', '', 'pc', 'system', '2020-09-21 16:03:56', '1');
INSERT INTO `luck_adver` VALUES ('6', '注册页广告', '', 'pc', 'system', '2020-09-21 16:05:43', '1');
INSERT INTO `luck_adver` VALUES ('7', 'Logo右侧广告', '春节乐购，抢1000元红包。', 'pc', 'system', '2021-03-02 15:31:22', '1');

-- --------------------------------------------------------

-- ----------------------------
-- 表结构 `luck_adver_value`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `luck_adver_value` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adver_id` int(11) NOT NULL DEFAULT '0',
  `image` varchar(255) NOT NULL DEFAULT '',
  `link` varchar(100) NOT NULL DEFAULT '' COMMENT '链接',
  `link_ident` tinyint(3) NOT NULL DEFAULT '1' COMMENT '链接标识 1=内部页面/当前页打开 2=外部链接/新窗口打开',
  `sort` mediumint(8) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- 转存表中的数据 luck_adver_value
-- ----------------------------
INSERT INTO `luck_adver_value` VALUES ('26', '2', '/images/adver/电脑首页轮播/1[luck1600152307luck].jpg', '', '1', '0');
INSERT INTO `luck_adver_value` VALUES ('27', '2', '/images/adver/电脑首页轮播/2[luck1600152310luck].jpg', '', '1', '0');
INSERT INTO `luck_adver_value` VALUES ('32', '3', '/images/adver/adver_demo[luck1600151282luck].jpg', 'http://baidu.com', '2', '0');
INSERT INTO `luck_adver_value` VALUES ('33', '3', '/images/adver/adver_demo[luck1600151282luck].jpg', 'http://lh1010.com', '1', '0');
INSERT INTO `luck_adver_value` VALUES ('34', '3', '/images/adver/adver_demo[luck1600151282luck].jpg', '', '1', '0');
INSERT INTO `luck_adver_value` VALUES ('35', '3', '/images/adver/adver_demo[luck1600151282luck].jpg', '', '1', '0');
INSERT INTO `luck_adver_value` VALUES ('36', '5', '/images/adver/login_adver[luck1600675432luck].jpg', '', '1', '0');
INSERT INTO `luck_adver_value` VALUES ('37', '6', '/images/adver/register_adver[luck1600675538luck].jpg', '', '1', '0');
INSERT INTO `luck_adver_value` VALUES ('38', '4', '/images/adver/游戏手机[luck1600153363luck].jpg', '', '1', '0');
INSERT INTO `luck_adver_value` VALUES ('39', '1', '/images/adver/mobile banner/banner1[luck1605506219luck].jpg', '/pages/product_list/index', '1', '0');
INSERT INTO `luck_adver_value` VALUES ('40', '1', '/images/adver/mobile banner/banner2[luck1605506225luck].jpg', '', '1', '0');
INSERT INTO `luck_adver_value` VALUES ('41', '1', '/images/adver/mobile banner/banner3[luck1605506227luck].jpg', '', '1', '0');
INSERT INTO `luck_adver_value` VALUES ('49', '7', '/images/adver/logo_right_adver[luck1614670270luck].gif', '', '1', '0');

-- --------------------------------------------------------

-- ----------------------------
-- 表结构 `luck_article`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `luck_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(150) NOT NULL DEFAULT '' COMMENT '标题',
  `author` varchar(100) NOT NULL DEFAULT '' COMMENT '作者',
  `source` varchar(100) NOT NULL DEFAULT '' COMMENT '来源',
  `keyword` varchar(100) NOT NULL DEFAULT '' COMMENT '关键字',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '简介',
  `thumbnail` varchar(255) NOT NULL DEFAULT '' COMMENT '缩略图',
  `content` text COMMENT '内容',
  `sort` mediumint(8) NOT NULL DEFAULT '0' COMMENT '排序',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '0=Disabled 1=Enabled',
  PRIMARY KEY (`id`),
  KEY `article_id_index` (`id`) USING BTREE,
  KEY `article_title_index` (`title`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COMMENT='文档';

-- ----------------------------
-- 转存表中的数据 luck_article
-- ----------------------------
INSERT INTO `luck_article` VALUES ('1', '3', '隐私条款', '', '', '', '', '', '<p>&nbsp;尊敬的用户：<br><br>在您使用【LuckMall】提供的服务之前，请您认真阅读本隐私政策，更好的了解我们所提供的服务以及您享有的权利义务。您开始使用LuckMall提供的服务，即表示您已经确认并接受了本文件中的全部条款。</p><p><br></p><p><b>一、前言<br></b></p><p><br></p><p>LuckMall非常重视用户的个人隐私，并致力于保护用户的个人信息，希望与用户之间建立真诚的信任关系，帮助信息发布者和用户更好地匹配。</p><p><br>在您使用LuckMall软件的服务时，LuckMall可能会收集和使用您的相关信息。本《隐私政策》将详细解释，我们在提供服务的过程中如何收集、储存和使用这些相关信息，以及我们为您提供的控制和保护这些信息的方式。</p><p><br>本《隐私政策》与您所使用的LuckMall服务息息相关，希望您仔细阅读。当您使用或继续使用我们的服务，即表明同意我们按照本《隐私政策》收集、储存和使用您的相关信息。 如果您对本隐私政策有任何问题或建议，请通过QQ610392592与我们联系。<br><br></p><p><b>二、我们可能收集到的信息</b></p><p><b><br></b></p><p>在您使用LuckMall产品的过程中，我们可能会收集部分使用信息并发往我们的服务器。我们的服务器收到的信息可划分为两类，一类是关于用户如何使用LuckMall产品的信息，另外一类是用户所拥有或提交数据的汇总统计信息。我们收集信息是为了向所有用户提供更好的服务，其中既包括一些基本信息（例如您的使用频率），也包括一些较为复杂的信息（例如您对哪些招聘或活动更感兴趣）。<br>我们会收集关于您使用的服务以及使用方式的信息，例如您在何时使用了软件、您的使用频率、您更偏好的使用时段等。以下是LuckMall软件可能会获取到的和您个人信息相关的清单。<br>指您使用我们的服务时，系统可能通过cookies或其他方式自动采集的技术信息，包括：<br>a、硬件配置及软件版本。例如您的移动设备、网页浏览器或用于接入我们服务的其他程序所提供的配置信息、您的IP地址和移动设备所用的操作系统、版本和设备识别码（IMEI）；<br>b、LuckMall软件及其他软件的信息。例如LuckMall软件的版本，为使用LuckMall的部分功能而调用其他软件的信息；<br>c、关于LuckMall软件功能的信息。例如LuckMall在使用过程中是否出现崩溃等软件故障，您对产品提出的反馈与建议等，便于我们对产品进行改进；<br>d、各功能的使用统计。我们使用这些数据来判断您对LuckMall软件内各个功能的喜爱程度，评估我们的产品是否符合您的使用习惯，并做出相应的改进；<br>e、数据同步、分享和储存。LuckMall提供的某些服务允许您同步、分享和储存数据，我们将会收集并储存您选择要上传、下载或实现这类功能的数据。<br>f、在使用我们的服务时搜索或浏览的信息。例如您在软件内搜索的关键词、浏览过的信息，以及您在使用我们服务时浏览或要求提供的其他信息和内容详情。<br><br><b>三、隐私政策的适用范围本</b></p><p><b><br></b></p><p>《隐私政策》适用LuckMall软件内的所有服务。但是针对某些特定服务的隐私政策，我们将更具体地说明在该服务中如何使用您的信息。如相关特定服务的隐私政策与本《隐私政策》有不一致之处，适用该特定服务的隐私政策。<br>请您注意，本《隐私政策》不适用于以下情况：<br>a、通过我们的服务而接入的第三方服务（包括任何第三方网站）收集的信息；<br>b、通过在我们服务中进行广告服务的其他公司或机构所收集的信息。<br><br><b>四、变更</b></p><p><b><br></b></p><p>我们可能适时修订本《隐私政策》的条款，我们将在修订生效前通过在软件内显著位置提示，或向您发送电子邮件，或以其他方式通知您。在向您进行通知之后，若您继续使用我们的服务，即表示同意受经修订的本《隐私政策》的约束。 <br></p>', '0', '2020-07-28 14:46:56', '2021-01-25 10:15:46', '1');
INSERT INTO `luck_article` VALUES ('3', '3', '用户协议', '', '', '', '', '', '<p>这里是用户协议内容<br></p>', '0', '2020-07-28 14:47:04', '2020-10-24 01:16:19', '1');
INSERT INTO `luck_article` VALUES ('2', '3', '关于我们', '', '', '', '', '', '<p>LuckMall是一款100%开放源码的PHP商城系统，基于ThinkPHP5开发，容易扩展，且具有强大的负载能力和稳定性。LuckMall基于Apache2.0开源协议发布，免费且不限制商业使用。用户可以自由修改，打造符合自己意愿的电商平台。</p><p><br><b>使用须知</b><br>1，LuckMall是一款免费的商城系统，在您确定使用后，我们将提供完整的源代码。在您使用过程中，请严格遵从法律法规，后续使用过程中所引起的法律责任，与LuckMall无关。<br>2，LuckMall演示站中的图片及内容均来自互联网，侵权请联系删除。</p><p><br><b>技术支持</b><br>QQ：610392592</p>', '0', '2020-07-28 14:46:17', '2021-01-25 11:09:11', '1');
INSERT INTO `luck_article` VALUES ('6', '5', '购物流程', '', '', '', '', '', '<p>一、新用户注册<br>1. 打开翼商城首页，在左上方，点击“免费注册”按钮；<br>2. 进入到注册页面，请填写您的邮箱、手机等信息完成注册；<br>3. 注册成功后，请完成账户安全验证，来提高您的账户安全等级。<br><br>二、挑选商品<br>优选为您提供了方便快捷的商品搜索功能：<br>方式一：通过搜索功能查找。在搜索框中输入关键字、商品名称或商品编码进行搜索。<br>方式二：通过分类导航查找。<br><br>三、确认下单<br>第一步：挑选到心仪商品后，可将商品放入购物车。点击“放入购物车”进入购物车页面。<br>第二步：在购物车中确认商品的数量、金额等信息无误后进行结算。<br>第三步：按提示填写详细的配送地址、配送方式、支付方式、送货时间。相关信息确定后，提交订单。<br>第四步：订单提交成功后，网站将出现相应提示，选择网上支付的订单，请在下单4小之内完成支付。<br><br>四、订单跟踪<br>在等待收货过程中，您可以在网站查询订单状态，了解订单处理及商品配送进度。<br>第一步：登录“我的信息”查询订单状态。<br>第二步：在订单详细页中可查看“订单配送信息”。<br><br>五、验货签收<br>验货<br>为保障您的合法权益，请您在签收前，务必确认以下信息：<br>1、订单编号是否正确；<br>2、收件人姓名是否是您本人；<br>3、查看翼商城发货单，核对商品品牌、型号、数量等内容是否与您下单时完全一致；<br>4、如果您选择货到付款，订单上标明的费用是否与您在下单时核对的费用完全一致；<br>5、商品是否存在任何异常（例如变形、损坏、渗漏等）。<br>签收<br>如果您在验货后未发现异常，即可签收。<br>如果您在验货后发现有异常，请向业务员指出，您可现场拒收整个包裹，也可以先对包裹做签收，再针对存在异常的商品做退换货处理，具体说明请参见退换货政策。<br>注意：如您在业务员离开后再提出包裹外包装或封带异常，以及商品的规格、数量或外观等存在异常而要求退换货，翼商城将无法为您处理，敬请谅解。除非您发现并有证据证明商品存在质量问题，此时请与翼商城客服中心000-00-000000联系。<br></p>', '0', '2020-10-24 01:11:09', '2020-10-26 15:03:59', '1');
INSERT INTO `luck_article` VALUES ('12', '7', '配送范围', '', '', '', '', '', '<p>一、配送范围<br>整个秦皇岛市：<br>1、快速配送范围：秦皇岛市海港区，开发区，北戴河区<br>2、同城快递配送：县城以及山海关区，北戴河新区等。（前期通过同城快递进行发货，最晚次日送达。后期我们的物流团队会迅速覆盖，可以快速配送）<br>&nbsp;<br>二、配送时间<br>前一天16：00—当日10:30下单，9点开始配送；<br>当日11:00—16:00下单，当日13点开始配送，正常2小时内送达。<br>蔬菜水果等生鲜订单，为保证新鲜，全部是当天订单第二天统一配送。<br>物流配送人员根据订单合理安排配送路线，如遇恶劣天气或其它不可抗力因素则协商解决。<br><br>三、运费标准<br>快速配送范围内：满49元包邮，未满49元收取配送费6元；<br>同城快递配送范围内：满99元包邮，未满99元收取同城快递费6元。<br><br>四、服务协助<br>当您在接收商品时，遇到任何问题时，稍安勿躁，请您拨打客服热线或者联系客服QQ：<br>联系电话：400-99-52001<br>客服QQ：3030705565<br>【客服工作时间：8:30-20:00】<br>所有问题由客服人员协助您解决，请勿直接与配送人员私下交涉，防止给您带来不便。<br><br>五、温馨提示<br>请您在签收时要注意检查商品是否正确，完好。如您正常签收商品，则默认为您确认商品无误，此订单交易正常完成。<br>如您收货后发现商品的重量不足、新鲜度不鲜、商品不符等问题，懒到家网上超市经核实后将全权负责，直到您最后满意。<br>懒到家网上超市成立之初，还有很多需要完善和改进的地方，希望您多提宝贵意见，支持懒到家网上超市的发展。<br>您满意的笑容，是我们最大的光荣！为此，我们不辱使命！！<br></p>', '0', '2020-10-26 15:08:36', '2020-10-26 15:51:13', '1');
INSERT INTO `luck_article` VALUES ('7', '5', '订单查询', '', '', '', '', '', '<p>第一步：登录“我的信息”查询订单状态。<br></p><p>第二步：在订单详细页中可查看“订单配送信息”。</p>', '0', '2020-10-24 01:11:35', '2020-10-24 01:11:35', '1');
INSERT INTO `luck_article` VALUES ('8', '5', '常见问题', '', '', '', '', '', '<p>1、下完订单后在账户里看不见相关信息怎么办？<br>您可能在翼商城有多个账户，建议您核实一下当时下订单的具体账户，如有疑问您可致电客服400-99-00001，帮您核查。</p><p><br>2、网站显示有赠品为何下单后没有收到赠品？<br>赠品的配送是和您的收货地址有关的，若您在浏览商品时用的地址非最终的收货地址，有可能出现下单后没有赠品的情况；您所在的地址是否支持赠品配送，请以结算页面的购物明细为准，谢谢。<br></p>', '0', '2020-10-24 01:11:57', '2020-10-26 15:02:06', '1');
INSERT INTO `luck_article` VALUES ('9', '6', '网上支付', '', '', '', '', '', '<p>LuckMall为您提供支付宝、支付宝网银直连、微信支付、银联在线、货到付款五种平台支付方式，您可以根据需要选择不同的支付平台进行支付。</p>', '0', '2020-10-24 01:12:40', '2020-10-24 01:12:40', '1');
INSERT INTO `luck_article` VALUES ('10', '6', '货到付款', '', '', '', '', '', '<p>您的收货地址是否支持货到付款，请以订单结算页为准，若订单结算页下支付方式中可以选择“货到付款”，则支持“货到付款”，否则不支持。</p>', '0', '2020-10-24 01:12:56', '2020-10-24 01:12:56', '1');
INSERT INTO `luck_article` VALUES ('11', '6', '公司转账', '', '', '', '', '', '<p>1，汇款时请备注汇款识别码，用于保证订单及时核销。此识别码务必填写正确，请勿增加其他文字说明；</p><p><br>2，汇款识别码需填写只电汇凭证的【汇款用途】、【附言】等栏内（因不同银行备注不同，最好在所有可填写备注的地方均填写）；</p><p><br>3，一个识别码对应一个订单和金额，请勿多转账或少转账；</p><p><br>4，请您在7天内完成支付，公司转账订单款项对账日期为3个工作日（从支付之日算起），10天内（从下单之日算起）如果还未付款并到账，系统将自动取消订单。</p>', '0', '2020-10-24 01:13:17', '2020-10-26 15:06:37', '1');
INSERT INTO `luck_article` VALUES ('13', '7', '订单进度查询', '', '', '', '', '', '<p>第一步：登录“我的信息”查询订单状态。</p><p>第二步：在订单详细页中可查看“订单配送信息”。</p>', '0', '2020-10-26 15:09:25', '2020-10-26 15:09:25', '1');
INSERT INTO `luck_article` VALUES ('14', '7', '验货与签收', '', '', '', '', '', '一、发货<br><br>1、 发货时间<br>1.1卖家应当在懒到家规定的时间内发货，但有特殊规定的除外。<br>1.2消费者申请退款时卖家尚未发货的，卖家应当征得消费者同意后再发货。<br>1.3卖家延期发货，或者未经消费者同意在消费者申请退款后发货，卖家应当追回已经发出的商品，但消费者已经签收并确认收货的除外。<br><br>2、 发货信息的确认<br>2.1除买卖双方另有约定外，卖家应当按照订单约定的收货地址、收货人信息发货，并负责将商品送达到消费者指定的收货地址。<br>2.2如商品需要消费者到指定地点提取的，应当在商品页面显著位置描述予以说明，并在发货前告知消费者并征得消费者同意。卖家违反前述规定的，消费者有权拒绝签收商品。<br>2.3卖家在发货后应及时将商品信息、快递信息在交易相关页面予以更新。<br><br>3、 收货信息的填写与变更<br>3.1消费者应当在订单中向卖家提供准确的收货地址和收货人信息。消费者在提供收货人信息时，可以选择本人或者他人作为收货人。消费者选择他人作为收货人，该收货人违反本规范约定义务的，由消费者承担相应责任。<br>3.2消费者需要变更订单中的收货地址或收货人信息的，应当在商品发出前与卖家取得联系征得卖家的明确同意，或协商进行处理。<br>3.3因消费者填写的收货地址和(或)收货人信息不准确，或者未经卖家同意要求变更收货地址或收货人信息，导致卖家发货后商品无法送达的，运费由消费者承担。<br><br>二、 配送与收货<br><br>1、 商品配送<br>1.1卖家所选择的快递公司在派送商品时应当主动联系收货人，而不应在未征得收货人同意的情况下将商品交由小区保安、门卫、公司前台等人签收。<br>1.2卖家应与快递公司约定，在提供配送过程中，应遵守懒到家平台相关规则规定的标准及规范，为消费者提供满意的配送服务，积极提升客户满意度。<br><br>2、 商品签收<br>2.1卖家按照约定发货后，收货人有及时收货的义务。收货人可以本人签收商品或委托他人代为签收商品，被委托人的签收视为收货人本人签收。<br>2.2消费者只填写了收货地址，但没有填写收货人或填写的收货人信息不特定，商品在收货地址被签收的，该签收视为消费者本人签收。<br><br>3、 商品验收<br>3.1收货人签收商品时，应当对商品进行验收。<br>3.2涉及商品表面一致的事项，收货人应当在签收商品时进行验收。 本条所称“表面一致”，是指凭肉眼即可判断所收到的商品表面状况良好且与网上描述相符，表面一致的判断范围包括但不限于商品的形状、大小、数量、重量等。<br>3.3收货人签收商品时发现表面不一致的，有权拒绝签收商品。 卖家应在商品拒收后及时联系消费者，进行处理。<br>3.4对于需要先签收再打开包装查看的商品，收货人应当要求承运人当场监督并打开包装查看，如发现表面不一致，应当在签收单（收货人联和承运人联）上备注详细情况并让承运人签字确认或者直接退回商品。<br>3.5收货人无正当理由拒绝签收商品的，运费由消费者承担。<br>3.6收货人拒绝签收商品后，卖家应当及时联系承运人取回商品。因卖家怠于取回商品所产生的额外运费、保管费等费用由卖家承担。<br><br>三、 风险转移<br><br>商品毁损、灭失等的风险自收货人签收商品后由卖家转移给收货人。<br><br>四、 违约处理<br><br>卖家违反本规范条款及懒到家平台相关规则约定而导致消费者投诉的，卖家应及时予以处理，若卖家未能妥善处理导致投诉扩大或未按照要求予以处理的，懒到家有权以普通人身份做出判断并要求卖家承担相应的责任。', '0', '2020-10-26 15:10:07', '2020-10-26 15:12:10', '1');
INSERT INTO `luck_article` VALUES ('15', '8', '退换货政策', '', '', '', '', '', '<p><span style=\"color: rgb(102, 102, 102); font-family: &quot;Microsoft Yahei&quot;, &quot;Lucida Grande&quot;, Verdana, Lucida, Helvetica, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\">1、顾客在平台提出退货申请，客服审核通过后，5—7个工作日内将货款退回到个人账户余额。邮费自理不予退款。</span><br style=\"margin: 0px; padding: 0px; outline: none; color: rgb(102, 102, 102); font-family: &quot;Microsoft Yahei&quot;, &quot;Lucida Grande&quot;, Verdana, Lucida, Helvetica, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial;\"><span style=\"color: rgb(102, 102, 102); font-family: &quot;Microsoft Yahei&quot;, &quot;Lucida Grande&quot;, Verdana, Lucida, Helvetica, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\">2、生鲜类商品请您在签收时做好检查工作，若有不满意可拒绝签收，逾期不予进行退货处理。</span><br style=\"margin: 0px; padding: 0px; outline: none; color: rgb(102, 102, 102); font-family: &quot;Microsoft Yahei&quot;, &quot;Lucida Grande&quot;, Verdana, Lucida, Helvetica, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial;\"><span style=\"color: rgb(102, 102, 102); font-family: &quot;Microsoft Yahei&quot;, &quot;Lucida Grande&quot;, Verdana, Lucida, Helvetica, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\">3、高档烟酒收货时请确认商品无误，此类商品一经售出，不予退换。</span><br style=\"margin: 0px; padding: 0px; outline: none; color: rgb(102, 102, 102); font-family: &quot;Microsoft Yahei&quot;, &quot;Lucida Grande&quot;, Verdana, Lucida, Helvetica, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial;\"><span style=\"color: rgb(102, 102, 102); font-family: &quot;Microsoft Yahei&quot;, &quot;Lucida Grande&quot;, Verdana, Lucida, Helvetica, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\">4、因顾客个人原因产生的退换货，所退换的商品要求完好无损，不可影响二次销售。</span><br style=\"margin: 0px; padding: 0px; outline: none; color: rgb(102, 102, 102); font-family: &quot;Microsoft Yahei&quot;, &quot;Lucida Grande&quot;, Verdana, Lucida, Helvetica, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial;\"><span style=\"color: rgb(102, 102, 102); font-family: &quot;Microsoft Yahei&quot;, &quot;Lucida Grande&quot;, Verdana, Lucida, Helvetica, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\">备注：最终解释权归翼商城网上超市所有。</span><br style=\"margin: 0px; padding: 0px; outline: none; color: rgb(102, 102, 102); font-family: &quot;Microsoft Yahei&quot;, &quot;Lucida Grande&quot;, Verdana, Lucida, Helvetica, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial;\"><span style=\"color: rgb(102, 102, 102); font-family: &quot;Microsoft Yahei&quot;, &quot;Lucida Grande&quot;, Verdana, Lucida, Helvetica, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\">如有其他问题请咨询客服：</span><br style=\"margin: 0px; padding: 0px; outline: none; color: rgb(102, 102, 102); font-family: &quot;Microsoft Yahei&quot;, &quot;Lucida Grande&quot;, Verdana, Lucida, Helvetica, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial;\"><span style=\"color: rgb(102, 102, 102); font-family: &quot;Microsoft Yahei&quot;, &quot;Lucida Grande&quot;, Verdana, Lucida, Helvetica, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\">联系电话：400-00-00000</span><br style=\"margin: 0px; padding: 0px; outline: none; color: rgb(102, 102, 102); font-family: &quot;Microsoft Yahei&quot;, &quot;Lucida Grande&quot;, Verdana, Lucida, Helvetica, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial;\"><span style=\"color: rgb(102, 102, 102); font-family: &quot;Microsoft Yahei&quot;, &quot;Lucida Grande&quot;, Verdana, Lucida, Helvetica, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\">客服QQ：610392592</span></p>', '0', '2020-10-26 15:15:17', '2020-10-26 15:15:17', '1');
INSERT INTO `luck_article` VALUES ('16', '8', '退换货流程', '', '', '', '', '', '<p>提交申请方式<br><br>如果您的订单需要办理退换货，请您先通过电话或邮件的方式提交申请。<br><br>客服电话：400-00-00000<br><br>客服邮件：610392592@qq.com<br><br>退换货说明<br><br>1、您提交的退换货申请，将由客服进行初审，审核通过的申请将会安排业务员上门取货和商品查验；<br><br>2、上门取货时业务人员将对退换货的商品进行查验，对于符合退换货政策的商品，将在商品收回后办理退款和换货发出；<br><br>退款说明<br><br>若办理退货，退款将以原订单的支付方式退回，货到付款的订单可选择银行转账退款。<br><br>退款到账需要一定时间，银行转账会根据不同银行间跨行转账的到账期而不同（财务办理退款成功后会短信通知您）。<br><br>注意事项<br><br>1、为您上门办理退换货时请您将所需退回商品（若订单中含有赠品需一并退回）、发票一起退还给业务人员；<br><br>2、翼商城为您提供上门退换货服务，不接受任何形式、任何公司的邮寄办理；<br><br>3、如您未经翼商城同意，直接将商品寄回翼商城的任何营业网点或办公网点，翼商城不承担保管、重新发货、退款责任，必要时翼商城有权处置滞留商品。<br></p>', '0', '2020-10-26 15:15:49', '2020-10-26 15:16:28', '1');
INSERT INTO `luck_article` VALUES ('17', '8', '退款说明', '', '', '', '', '', '<p><b>取消订单退款</b><br><br>懒到家为您提供自助取消订单服务，您可进入“我的信息—我的订单”针对问题订单自助操作取消。<br><br>1、&nbsp; 取消订单成功后，优惠券支付的部分，将实时返还到您的账户中（若当时未查询到返还退款，建议等待5分钟后再查看）；<br><br>2、&nbsp; 账户余额和在线支付的部分，将在退款审核通过后，退回您原支付账户。<br><br><b>退货订单退款</b><br><br>若您办理了订单退货，退货商品在入库后，我们会根据您原订单的支付方式来判断退款的方式。<br><br>1、&nbsp; 非货到付款支付： 退款将原渠道退回。<br><br>2、&nbsp; 货到付款支付：请在申请退货时，向客服人员提供接收退款的银行卡信息，我们会将货到付款支付的款项退至指定的银行卡内。<br><br><b>拒收订单退款</b><br><br>若您对收到的商品不满意，直接进行了拒收，那么在订单拒收入库后，我们会将您支付的款项按原渠道退回。<br><br><b>退款办理时效</b><br><br>退款审核通过后的退款方式及退款周期：<br><br>如有任何疑问您可致电懒到家客服热线400-99-52001，我们将竭诚为您服务。<br><br>注意：退款单处理周期仅供参考，具体退款到账时间可能会受银行、支付机构、业务高峰等相关因素影响，敬请谅解。<br><br><b>退款进度查询</b><br><br>您可登录“我的信息--交易管理--退款查询”自助查询订单的退款进度。<br></p>', '0', '2020-10-26 15:17:22', '2020-10-26 15:17:22', '1');
INSERT INTO `luck_article` VALUES ('18', '9', '商家入驻', '', '', '', '', '', '<p><b>平台服务/规则</b><br><br>平台服务：（即入住支持）<br><br>宣传物料：海报、宣传单页、贴纸、展架<br><br>技术支持：网络平台的设计、建设、维护、升级、标准商品数据、数据库建设维护<br><br>物流支持：协助招募管理配送人员<br><br>培训支持：后台操作培训、网络运营培训<br><br>活动推广：线上推广、社区会员招募、节日活动策划、促销活动策划、持续吸引客流<br><br>平台支持：PC电商平台、入驻移动手机端、大数据<br><br><b>平台规则：（即入驻条件）</b><br><br>意识观念：认同“翼商城”经营理念，认同行业互联网发展趋势。<br><br>门店租期：门店租赁有效期限一年以上。<br><br>门店面积：要求门店经营面积30平米以上。<br><br>平台费用：按商城现行服务标准执行。<br></p>', '0', '2020-10-26 15:18:55', '2020-10-26 15:18:55', '1');
INSERT INTO `luck_article` VALUES ('19', '9', '商家规则', '', '', '', '', '', '<p>本次修订主要涉及内容如下：<br><br>1.总则第二条【适用范围】增加详细描述，详细内容如下：<br><br>第二条【适用范围】本规范适用于接受平台服务的买家和卖家（包括但不限于其商品在平台搜索展现的卖家等，下同）。<br><br>2.总则第九条【交易限制】增加限制条件，详细内容如下：<br><br>第九条【交易限制】平台卖家与买家就其在平台已发布商品达成购买意向后，不得与买家在平台外进行交易，且不得要求买家支出约定外的任何其他成本（包括但不限于费用成本、时间成本、人力成本等），类目有特殊规定的从其规定。<br><br>3.分则国内机票篇第二条【发布管理】增加“0元换购的机票需按航司规定航位及该航位对应的退改签发布。”<br></p>', '0', '2020-10-26 15:19:38', '2020-10-26 15:19:38', '1');
INSERT INTO `luck_article` VALUES ('20', '9', '入驻流程', '', '', '', '', '', '<p><span style=\"color: rgb(102, 102, 102); font-family: &quot;Microsoft Yahei&quot;, &quot;Lucida Grande&quot;, Verdana, Lucida, Helvetica, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\">入驻流程入驻流程入驻流程入驻流程入驻流程入驻流程入驻流程入驻流程入驻流程入驻流程入驻流程入驻流程入驻流程入驻流程入驻流程入驻流程入驻流程入驻流程入驻流程入驻流程入驻流程入驻流程入驻流程入驻流程入驻流程入驻流程入驻流程入驻流程入驻流程入驻流程入驻流程入驻流程入驻流程入驻流程入驻流程入驻流程入驻流程入驻流程入驻流程入驻流程入驻流程入驻流程入驻流程入驻流程入驻流程入驻流程入驻流程入驻流程入驻流程</span></p>', '0', '2020-10-26 15:19:54', '2020-10-26 15:19:54', '1');
INSERT INTO `luck_article` VALUES ('21', '3', '联系我们', '', '', '', '', '', '<p>LuckMall是一款100%开放源码的PHP商城系统，基于ThinkPHP5开发，容易扩展，且具有强大的负载能力和稳定性。LuckMall基于Apache2.0开源协议发布，免费且不限制商业使用。用户可以自由修改，打造符合自己意愿的电商平台。</p><p><br><b>使用须知</b><br>1，LuckMall是一款免费的商城系统，在您确定使用后，我们将提供完整的源代码。在您使用过程中，请严格遵从法律法规，后续使用过程中所引起的法律责任，与LuckMall无关。<br>2，LuckMall演示站中的图片及内容均来自互联网，侵权请联系删除。</p><p><br><b>技术支持</b><br>QQ：610392592</p>', '0', '2021-01-25 10:00:28', '2021-01-25 11:11:50', '1');

-- --------------------------------------------------------

-- ----------------------------
-- 表结构 `luck_article_category`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `luck_article_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '类目名',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '上级ID',
  `sort` mediumint(8) NOT NULL DEFAULT '0' COMMENT '排序',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '类目描述',
  `type` varchar(20) NOT NULL DEFAULT '' COMMENT '类型 system=系统使用',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '0=Disabled 1=Enabled',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COMMENT='文档分类';

-- ----------------------------
-- 转存表中的数据 luck_article_category
-- ----------------------------
INSERT INTO `luck_article_category` VALUES ('3', '系统文档', '0', '0', '系统调用使用文档', 'system', '2020-07-28 14:44:58', '2021-02-23 17:20:03', '1');
INSERT INTO `luck_article_category` VALUES ('1', '帮助中心', '0', '0', '', 'system', '2020-10-23 16:27:55', '2021-02-23 17:20:16', '1');
INSERT INTO `luck_article_category` VALUES ('5', '新手上路', '1', '0', '', '', '2020-10-23 17:29:05', '2020-10-24 00:46:07', '1');
INSERT INTO `luck_article_category` VALUES ('6', '支付方式', '1', '0', '', '', '2020-10-24 00:46:46', '2020-10-24 00:46:46', '1');
INSERT INTO `luck_article_category` VALUES ('7', '配送服务', '1', '0', '', '', '2020-10-24 00:46:59', '2020-10-24 00:46:59', '1');
INSERT INTO `luck_article_category` VALUES ('8', '售后服务', '1', '0', '', '', '2020-10-24 00:47:12', '2020-10-24 00:47:12', '1');
INSERT INTO `luck_article_category` VALUES ('9', '商家合作', '1', '0', '', '', '2020-10-24 00:47:23', '2020-10-24 00:47:23', '1');

-- --------------------------------------------------------

-- ----------------------------
-- 表结构 `luck_brand`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `luck_brand` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `cover` varchar(255) NOT NULL DEFAULT '',
  `sort` mediumint(8) NOT NULL DEFAULT '0',
  `keyword` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '0=Disabled 1=Enabled',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- 转存表中的数据 luck_brand
-- ----------------------------
INSERT INTO `luck_brand` VALUES ('1', '小米', '/images/brand/xiaomi[luck1614326365luck].png', '0', '', '', '2021-02-26 15:59:30', '2021-02-26 15:59:30', '1');
INSERT INTO `luck_brand` VALUES ('2', '海信', '/images/brand/haixin[luck1614326736luck].png', '0', '', '', '2021-02-26 16:05:41', '2021-02-26 16:05:41', '1');
INSERT INTO `luck_brand` VALUES ('3', '格力', '/images/brand/geli[luck1614327478luck].png', '0', '', '', '2021-02-26 16:18:04', '2021-02-26 16:18:04', '1');

-- --------------------------------------------------------

-- ----------------------------
-- 表结构 `luck_cart`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `luck_cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `sku` varchar(200) NOT NULL DEFAULT '',
  `count` mediumint(8) NOT NULL DEFAULT '1',
  `selected` tinyint(3) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

-- ----------------------------
-- 表结构 `luck_design_nav`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `luck_design_nav` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `sort` mediumint(8) NOT NULL DEFAULT '0',
  `open_type` tinyint(3) NOT NULL DEFAULT '1' COMMENT '打开方式 1=当前页面 2=新窗口',
  `status` tinyint(3) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='菜单设置';

-- --------------------------------------------------------

-- ----------------------------
-- 表结构 `luck_friendlink`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `luck_friendlink` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `sort` mediumint(8) NOT NULL DEFAULT '0',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(3) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COMMENT='友情链接';

-- ----------------------------
-- 转存表中的数据 luck_friendlink
-- ----------------------------
INSERT INTO `luck_friendlink` VALUES ('1', '百度', 'http://baidu.com', '0', '2021-02-26 17:39:25', '1');
INSERT INTO `luck_friendlink` VALUES ('2', '京东', 'http://jd.com', '0', '2021-02-26 17:39:44', '1');
INSERT INTO `luck_friendlink` VALUES ('3', '天猫', 'http://tmall.com', '0', '2021-02-26 17:40:05', '1');
INSERT INTO `luck_friendlink` VALUES ('4', '唯品会', 'http://vip.com', '0', '2021-02-26 17:40:31', '1');

-- --------------------------------------------------------

-- ----------------------------
-- 表结构 `luck_integral_log`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `luck_integral_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `integral` int(20) NOT NULL DEFAULT '0',
  `ident` varchar(10) NOT NULL DEFAULT 'inc' COMMENT 'inc=加 dec=减',
  `description` varchar(255) NOT NULL DEFAULT '',
  `type` varchar(25) NOT NULL DEFAULT 'qiandao' COMMENT 'qiandao|exchange_product',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='积分赚取/消耗记录';

-- --------------------------------------------------------

-- ----------------------------
-- 表结构 `luck_integral_order`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `luck_integral_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `product_id` int(11) NOT NULL DEFAULT '0',
  `product_name` varchar(200) NOT NULL DEFAULT '',
  `product_image` varchar(255) NOT NULL DEFAULT '',
  `product_integral` int(20) NOT NULL DEFAULT '0',
  `name` varchar(20) NOT NULL DEFAULT '',
  `province_name` varchar(64) NOT NULL DEFAULT '',
  `city_name` varchar(64) NOT NULL DEFAULT '',
  `district_name` varchar(64) NOT NULL DEFAULT '',
  `detailed_address` text NOT NULL,
  `phone` varchar(20) NOT NULL DEFAULT '',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(20) NOT NULL DEFAULT '10',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='积分商品兑换订单';

-- --------------------------------------------------------

-- ----------------------------
-- 表结构 `luck_integral_product`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `luck_integral_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL DEFAULT '',
  `image` varchar(255) NOT NULL DEFAULT '',
  `content` text,
  `integral` int(20) NOT NULL DEFAULT '0',
  `sort` mediumint(8) NOT NULL DEFAULT '0',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '0=Disabled 1=Enabled',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='积分产品';

-- --------------------------------------------------------

-- ----------------------------
-- 表结构 `luck_mail_code`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `luck_mail_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL DEFAULT '',
  `code` varchar(10) NOT NULL DEFAULT '',
  `is_use` tinyint(3) NOT NULL DEFAULT '0',
  `type` varchar(50) NOT NULL DEFAULT '',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `index_phone` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='邮箱验证码';

-- --------------------------------------------------------

-- ----------------------------
-- 表结构 `luck_mail_log`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `luck_mail_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL DEFAULT '',
  `content` varchar(255) NOT NULL DEFAULT '',
  `type` varchar(50) NOT NULL DEFAULT '',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='邮件发送记录';

-- --------------------------------------------------------

-- ----------------------------
-- 表结构 `luck_order`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `luck_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `number` varchar(100) NOT NULL DEFAULT '',
  `payment_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(20) NOT NULL DEFAULT '',
  `province_id` int(11) NOT NULL DEFAULT '0',
  `province_name` varchar(64) NOT NULL DEFAULT '',
  `city_id` int(11) NOT NULL DEFAULT '0',
  `city_name` varchar(64) NOT NULL DEFAULT '',
  `district_id` int(11) NOT NULL DEFAULT '0',
  `district_name` varchar(64) NOT NULL DEFAULT '',
  `detailed_address` text NOT NULL,
  `phone` varchar(20) NOT NULL DEFAULT '0',
  `product_total_price` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT '产品总金额',
  `shipping_freight_total_price` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT '运费',
  `total_price` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT '订单总金额',
  `message` varchar(255) NOT NULL DEFAULT '' COMMENT '买家留言',
  `type` varchar(10) NOT NULL DEFAULT 'cart' COMMENT 'cart|onekeybuy',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` varchar(20) NOT NULL DEFAULT '10' COMMENT '-10=取消 10=待付款 20=已付款，未发货 30=已发货 40=已完成 99=删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

-- ----------------------------
-- 表结构 `luck_order_snap`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `luck_order_snap` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL DEFAULT '0',
  `sku` varchar(200) NOT NULL DEFAULT '',
  `product_id` int(11) NOT NULL DEFAULT '0',
  `product_name` varchar(255) NOT NULL,
  `product_image` varchar(255) NOT NULL DEFAULT '',
  `specifications` text NOT NULL,
  `count` int(11) NOT NULL DEFAULT '0' COMMENT '购买数量',
  `sale_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品单价',
  `total_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '购买总金额',
  `shipping_freight_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '运费',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='订单快照';

-- --------------------------------------------------------

-- ----------------------------
-- 表结构 `luck_order_tracking`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `luck_order_tracking` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL COMMENT '订单id',
  `shipping_mark_id` int(11) NOT NULL DEFAULT '0',
  `shipping_mark_name` varchar(100) NOT NULL DEFAULT '',
  `tracking_number` varchar(50) NOT NULL DEFAULT '' COMMENT '物流单号',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

-- ----------------------------
-- 表结构 `luck_payment_log`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `luck_payment_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `number` varchar(100) NOT NULL DEFAULT '',
  `order_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `payment_id` int(11) NOT NULL DEFAULT '0',
  `trade_no` varchar(255) DEFAULT '' COMMENT '第三方支付平台订单编号,支付成功存在',
  `subject` varchar(255) NOT NULL DEFAULT '' COMMENT '支付订单标题',
  `body` varchar(255) NOT NULL DEFAULT '' COMMENT '支付订单描述',
  `total_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '0=未支付 1=支付成功 2=退款成功',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

-- ----------------------------
-- 表结构 `luck_product`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `luck_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '产品名',
  `model_id` int(11) NOT NULL DEFAULT '0',
  `category_id` int(11) NOT NULL DEFAULT '0',
  `brand_id` int(11) NOT NULL DEFAULT '0',
  `content` text COMMENT '内容',
  `sort` mediumint(8) NOT NULL DEFAULT '0',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '0=Disabled 1=Enabled',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COMMENT='商品基础信息 商品SPU';

-- ----------------------------
-- 转存表中的数据 luck_product
-- ----------------------------
INSERT INTO `luck_product` VALUES ('1', '小米电视机4C43英寸全高清智能液晶屏网络家用平板彩电视官方旗舰', '0', '3', '1', '<p align=\"center\"><img style=\"width: 790px;\" src=\"/images/product/家用电器/c1[luck1614326448luck].jpg\"><br></p>', '0', '2021-02-26 16:01:29', '2021-02-26 16:12:06', '1');
INSERT INTO `luck_product` VALUES ('2', '小米电视4A55英寸4K超高清智能液晶屏平板家用网络彩电视机', '0', '3', '1', '<p align=\"center\"><img style=\"width: 750px;\" src=\"/images/product/家用电器/c1[luck1614326646luck].jpg\"><br></p>', '0', '2021-02-26 16:04:32', '2021-02-26 16:11:53', '1');
INSERT INTO `luck_product` VALUES ('3', 'Hisense/海信 H65E3A 65英寸4K高清智能网络平板液晶电视机', '0', '3', '2', '<p align=\"center\"><img style=\"width: 790px;\" src=\"/images/product/家用电器/c1[luck1614326907luck].jpg\"><br></p>', '0', '2021-02-26 16:08:34', '2021-02-26 16:11:41', '1');
INSERT INTO `luck_product` VALUES ('4', 'Hisense/海信 H55E3A 55英寸4K高清智能网络平板液晶电视机', '0', '3', '2', '<p align=\"center\"><img style=\"width: 790px;\" src=\"/images/product/家用电器/c1[luck1614326907luck].jpg\"><br></p>', '0', '2021-02-26 16:15:35', '2021-02-26 16:15:35', '1');
INSERT INTO `luck_product` VALUES ('5', '格力空调（GREE）冷暖自洁 壁挂式卧室空调挂机 KFR-26GW/NhPaB1W 京鱼座智能产品', '1', '4', '3', '<p align=\"center\"><img style=\"width: 750px;\" src=\"/images/product/家用电器/c1[luck1614330447luck].jpg\"><br></p>', '0', '2021-02-26 16:30:33', '2021-02-26 17:09:34', '1');
INSERT INTO `luck_product` VALUES ('6', '阿迪达斯短袖女2020夏季新款官网正品修身显瘦休闲半袖运动T恤女', '2', '33', '0', '<p align=\"center\"><img style=\"width: 800px;\" src=\"/images/product/女装/c1[luck1614331227luck].jpg\"><br></p>', '0', '2021-02-26 17:20:33', '2021-02-26 17:20:33', '1');
INSERT INTO `luck_product` VALUES ('7', '吸睛丝垂雪纺v领黑色衬衫女设计感小众长袖衬衣女2020新款上衣女', '2', '34', '0', '<p align=\"center\"><img style=\"width: 800px;\" src=\"/images/product/女装/2[luck1614331501luck].jpg\"><br></p>', '0', '2021-02-26 17:25:45', '2021-02-26 17:25:45', '1');
INSERT INTO `luck_product` VALUES ('8', '长款仙女裙子配大衣的内搭毛衣裙过膝红色针织打底裙连衣裙女秋冬', '2', '52', '0', '<p align=\"center\"><img style=\"width: 750px;\" src=\"/images/product/女装/3[luck1614331846luck].jpg\"><br></p>', '0', '2021-02-26 17:31:29', '2021-02-26 17:31:29', '1');
INSERT INTO `luck_product` VALUES ('9', '白色雪纺连衣裙女夏天2020新款气质大码女装遮肚显瘦减龄仙气长裙', '2', '52', '0', '<p align=\"center\"><img style=\"width: 750px;\" src=\"/images/product/女装/3[luck1614332279luck].jpg\"></p><p align=\"center\"><img style=\"width: 750px;\" src=\"/images/product/女装/5[luck1614332279luck].jpg\"><br></p>', '0', '2021-02-26 17:38:51', '2021-02-26 17:38:51', '1');
INSERT INTO `luck_product` VALUES ('10', '2020秋装新款长袖上衣缎面白色加绒衬衫女设计感小众雪纺职业衬衣', '2', '34', '0', '<p align=\"center\"><img style=\"width: 790px;\" src=\"/images/product/女装/c1[luck1614332997luck].jpg\"></p><p align=\"center\"><img style=\"width: 790px;\" src=\"/images/product/女装/c2[luck1614332997luck].jpg\"></p><p align=\"center\"><img style=\"width: 790px;\" src=\"/images/product/女装/c3[luck1614332997luck].jpg\"><br></p>', '0', '2021-02-26 17:48:55', '2021-02-27 01:02:34', '1');

-- --------------------------------------------------------

-- ----------------------------
-- 表结构 `luck_product_attribute`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `luck_product_attribute` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_model_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL DEFAULT '',
  `type` varchar(100) NOT NULL DEFAULT 'select' COMMENT 'select|string',
  `sort` mediumint(8) NOT NULL DEFAULT '0',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '1=Enabled 0=Disabled 99=Delete',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='商品属性';

-- --------------------------------------------------------

-- ----------------------------
-- 表结构 `luck_product_attribute_option`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `luck_product_attribute_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_attribute_id` int(11) NOT NULL DEFAULT '0',
  `value` varchar(100) NOT NULL DEFAULT '',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '1=Enabled 99=Delete',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='商品属性选项';

-- --------------------------------------------------------

-- ----------------------------
-- 表结构 `luck_product_category`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `luck_product_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `child_ids` text,
  `parent_ids` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(100) NOT NULL DEFAULT '',
  `keyword` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `sort` mediumint(8) NOT NULL DEFAULT '0',
  `wxapp_cover` varchar(255) NOT NULL DEFAULT '',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '1=Enabled 0=Disabled',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COMMENT='产品分类';

-- ----------------------------
-- 转存表中的数据 luck_product_category
-- ----------------------------
INSERT INTO `luck_product_category` VALUES ('1', '0', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30', '1', '家用电器', '', '', '0', '/images/adver/mobile banner/banner1[luck1605506219luck].jpg', '2021-02-26 15:20:42', '2021-03-05 14:58:02', '1');
INSERT INTO `luck_product_category` VALUES ('2', '1', '2,3,4,5,6', '1,2', '大家电', '', '', '0', '', '2021-02-26 15:20:53', '2021-03-05 14:28:15', '1');
INSERT INTO `luck_product_category` VALUES ('3', '2', '3', '1,2,3', '电视', '', '', '0', '/images/product_category/mobile_app/电视[luck1614927751luck].jpg', '2021-02-26 15:21:07', '2021-03-05 15:02:34', '1');
INSERT INTO `luck_product_category` VALUES ('4', '2', '4', '1,2,4', '空调', '', '', '0', '/images/product_category/mobile_app/空调[luck1614927793luck].jpg', '2021-02-26 15:21:12', '2021-03-05 15:03:14', '1');
INSERT INTO `luck_product_category` VALUES ('5', '2', '5', '1,2,5', '洗衣机', '', '', '0', '/images/product_category/mobile_app/洗衣机[luck1614928164luck].jpg', '2021-02-26 15:22:10', '2021-03-05 15:09:25', '1');
INSERT INTO `luck_product_category` VALUES ('6', '2', '6', '1,2,6', '冰箱', '', '', '0', '', '2021-02-26 15:22:18', '2021-02-26 15:22:18', '1');
INSERT INTO `luck_product_category` VALUES ('7', '1', '7,8,9,10,11,12,13', '1,7', '厨卫大电', '', '', '0', '', '2021-02-26 15:22:32', '2021-02-26 15:23:16', '1');
INSERT INTO `luck_product_category` VALUES ('8', '7', '8', '1,7,8', '油烟机', '', '', '0', '', '2021-02-26 15:22:39', '2021-02-26 15:22:39', '1');
INSERT INTO `luck_product_category` VALUES ('9', '7', '9', '1,7,9', '燃气灶', '', '', '0', '', '2021-02-26 15:22:46', '2021-02-26 15:22:46', '1');
INSERT INTO `luck_product_category` VALUES ('10', '7', '10', '1,7,10', '消毒柜', '', '', '0', '', '2021-02-26 15:22:52', '2021-02-26 15:22:52', '1');
INSERT INTO `luck_product_category` VALUES ('11', '7', '11', '1,7,11', '洗碗机', '', '', '0', '', '2021-02-26 15:23:00', '2021-02-26 15:23:00', '1');
INSERT INTO `luck_product_category` VALUES ('12', '7', '12', '1,7,12', '电热水器', '', '', '0', '', '2021-02-26 15:23:07', '2021-02-26 15:23:07', '1');
INSERT INTO `luck_product_category` VALUES ('13', '7', '13', '1,7,13', '燃气热水器', '', '', '0', '', '2021-02-26 15:23:16', '2021-02-26 15:23:16', '1');
INSERT INTO `luck_product_category` VALUES ('14', '1', '14,15,16,17,18,19,20', '1,14', '厨卫小电', '', '', '0', '', '2021-02-26 15:23:32', '2021-02-26 15:24:18', '1');
INSERT INTO `luck_product_category` VALUES ('15', '14', '15', '1,14,15', '电饭煲', '', '', '0', '', '2021-02-26 15:23:40', '2021-02-26 15:23:40', '1');
INSERT INTO `luck_product_category` VALUES ('16', '14', '16', '1,14,16', '微波炉', '', '', '0', '', '2021-02-26 15:23:48', '2021-02-26 15:23:49', '1');
INSERT INTO `luck_product_category` VALUES ('17', '14', '17', '1,14,17', '电烤箱', '', '', '0', '', '2021-02-26 15:23:55', '2021-02-26 15:23:55', '1');
INSERT INTO `luck_product_category` VALUES ('18', '14', '18', '1,14,18', '电磁炉', '', '', '0', '', '2021-02-26 15:24:02', '2021-02-26 15:24:02', '1');
INSERT INTO `luck_product_category` VALUES ('19', '14', '19', '1,14,19', '电压力锅', '', '', '0', '', '2021-02-26 15:24:08', '2021-02-26 15:24:08', '1');
INSERT INTO `luck_product_category` VALUES ('20', '14', '20', '1,14,20', '豆浆机', '', '', '0', '', '2021-02-26 15:24:18', '2021-02-26 15:24:18', '1');
INSERT INTO `luck_product_category` VALUES ('21', '1', '21,22,23,24,25,26,27,28,29,30', '1,21', '生活电器', '', '', '0', '', '2021-02-26 15:24:28', '2021-02-26 15:25:38', '1');
INSERT INTO `luck_product_category` VALUES ('22', '21', '22', '1,21,22', '电风扇', '', '', '0', '', '2021-02-26 15:24:35', '2021-02-26 15:24:35', '1');
INSERT INTO `luck_product_category` VALUES ('23', '21', '23', '1,21,23', '饮水机', '', '', '0', '', '2021-02-26 15:24:43', '2021-02-26 15:24:43', '1');
INSERT INTO `luck_product_category` VALUES ('24', '21', '24', '1,21,24', '净水器', '', '', '0', '', '2021-02-26 15:24:50', '2021-02-26 15:24:50', '1');
INSERT INTO `luck_product_category` VALUES ('25', '21', '25', '1,21,25', '空气净化器', '', '', '0', '', '2021-02-26 15:24:58', '2021-02-26 15:24:58', '1');
INSERT INTO `luck_product_category` VALUES ('26', '21', '26', '1,21,26', '扫地机器人', '', '', '0', '', '2021-02-26 15:25:05', '2021-02-26 15:25:05', '1');
INSERT INTO `luck_product_category` VALUES ('27', '21', '27', '1,21,27', '加湿器', '', '', '0', '', '2021-02-26 15:25:12', '2021-02-26 15:25:12', '1');
INSERT INTO `luck_product_category` VALUES ('28', '21', '28', '1,21,28', '挂烫机/熨斗', '', '', '0', '', '2021-02-26 15:25:24', '2021-02-26 15:25:24', '1');
INSERT INTO `luck_product_category` VALUES ('29', '21', '29', '1,21,29', '清洁机', '', '', '0', '', '2021-02-26 15:25:31', '2021-02-26 15:25:32', '1');
INSERT INTO `luck_product_category` VALUES ('30', '21', '30', '1,21,30', '插座', '', '', '0', '', '2021-02-26 15:25:38', '2021-02-26 15:25:39', '1');
INSERT INTO `luck_product_category` VALUES ('31', '0', '31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52', '31', '女装', '', '', '0', '/images/adver/mobile banner/banner2[luck1605506225luck].jpg', '2021-02-26 15:28:48', '2021-03-05 15:06:31', '1');
INSERT INTO `luck_product_category` VALUES ('32', '31', '32,33,34,35,36,37,38', '31,32', '上装', '', '', '0', '', '2021-02-26 15:29:17', '2021-02-26 15:30:37', '1');
INSERT INTO `luck_product_category` VALUES ('33', '32', '33', '31,32,33', '字母T恤', '', '', '0', '/images/product_category/mobile_app/字母T恤[luck1614928052luck].jpg', '2021-02-26 15:29:27', '2021-03-05 15:07:34', '1');
INSERT INTO `luck_product_category` VALUES ('34', '32', '34', '31,32,34', '衬衫', '', '', '0', '/images/product_category/mobile_app/白衬衫[luck1614928069luck].jpg', '2021-02-26 15:29:38', '2021-03-05 15:07:51', '1');
INSERT INTO `luck_product_category` VALUES ('35', '32', '35', '31,32,35', '百搭衬衫', '', '', '0', '/images/product_category/mobile_app/百搭衬衫[luck1614928118luck].jpg', '2021-02-26 15:29:46', '2021-03-05 15:08:40', '1');
INSERT INTO `luck_product_category` VALUES ('36', '32', '36', '31,32,36', '雪纺衫', '', '', '0', '', '2021-02-26 15:30:20', '2021-02-26 15:30:20', '1');
INSERT INTO `luck_product_category` VALUES ('37', '32', '37', '31,32,37', '打底衫', '', '', '0', '', '2021-02-26 15:30:28', '2021-02-26 15:30:28', '1');
INSERT INTO `luck_product_category` VALUES ('38', '32', '38', '31,32,38', '长袖T恤', '', '', '0', '', '2021-02-26 15:30:37', '2021-02-26 15:30:37', '1');
INSERT INTO `luck_product_category` VALUES ('39', '31', '39,40,41,42,43,44,45', '31,39', '下装', '', '', '0', '', '2021-02-26 15:30:45', '2021-02-26 15:31:31', '1');
INSERT INTO `luck_product_category` VALUES ('40', '39', '40', '31,39,40', '铅笔裤', '', '', '0', '', '2021-02-26 15:30:53', '2021-02-26 15:30:53', '1');
INSERT INTO `luck_product_category` VALUES ('41', '39', '41', '31,39,41', '小脚裤', '', '', '0', '', '2021-02-26 15:31:01', '2021-02-26 15:31:01', '1');
INSERT INTO `luck_product_category` VALUES ('42', '39', '42', '31,39,42', '休闲裤', '', '', '0', '', '2021-02-26 15:31:08', '2021-02-26 15:31:08', '1');
INSERT INTO `luck_product_category` VALUES ('43', '39', '43', '31,39,43', '阔腿裤', '', '', '0', '', '2021-02-26 15:31:16', '2021-02-26 15:31:16', '1');
INSERT INTO `luck_product_category` VALUES ('44', '39', '44', '31,39,44', '牛仔裤', '', '', '0', '', '2021-02-26 15:31:23', '2021-02-26 15:31:23', '1');
INSERT INTO `luck_product_category` VALUES ('45', '39', '45', '31,39,45', '哈伦裤', '', '', '0', '', '2021-02-26 15:31:31', '2021-02-26 15:31:31', '1');
INSERT INTO `luck_product_category` VALUES ('46', '31', '46,47,48,49,50,51,52', '31,46', '裙装', '', '', '0', '', '2021-02-26 15:31:39', '2021-02-26 15:32:26', '1');
INSERT INTO `luck_product_category` VALUES ('47', '46', '47', '31,46,47', '半身裙', '', '', '0', '', '2021-02-26 15:31:47', '2021-02-26 15:31:47', '1');
INSERT INTO `luck_product_category` VALUES ('48', '46', '48', '31,46,48', '套装裙', '', '', '0', '', '2021-02-26 15:31:55', '2021-02-26 15:31:55', '1');
INSERT INTO `luck_product_category` VALUES ('49', '46', '49', '31,46,49', '长袖连衣裙', '', '', '0', '', '2021-02-26 15:32:03', '2021-02-26 15:32:03', '1');
INSERT INTO `luck_product_category` VALUES ('50', '46', '50', '31,46,50', '性感连衣裙', '', '', '0', '', '2021-02-26 15:32:11', '2021-02-26 15:32:11', '1');
INSERT INTO `luck_product_category` VALUES ('51', '46', '51', '31,46,51', '蕾丝连衣裙', '', '', '0', '', '2021-02-26 15:32:19', '2021-02-26 15:32:19', '1');
INSERT INTO `luck_product_category` VALUES ('52', '46', '52', '31,46,52', '连衣裙', '', '', '0', '', '2021-02-26 15:32:26', '2021-02-26 15:32:26', '1');

-- --------------------------------------------------------

-- ----------------------------
-- 表结构 `luck_product_freight`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `luck_product_freight` (
  `product_id` int(11) NOT NULL DEFAULT '0',
  `freight` tinyint(3) NOT NULL DEFAULT '1' COMMENT '是否包邮 1=包邮 2=不包邮',
  `freight_id` int(11) NOT NULL DEFAULT '0',
  `weight` varchar(50) NOT NULL DEFAULT '' COMMENT '重量 单位克',
  `volume` varchar(50) NOT NULL DEFAULT '' COMMENT '体积 单位立方米'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- 转存表中的数据 luck_product_freight
-- ----------------------------
INSERT INTO `luck_product_freight` VALUES ('1', '1', '0', '', '');
INSERT INTO `luck_product_freight` VALUES ('2', '1', '0', '', '');
INSERT INTO `luck_product_freight` VALUES ('3', '1', '0', '', '');
INSERT INTO `luck_product_freight` VALUES ('4', '1', '0', '', '');
INSERT INTO `luck_product_freight` VALUES ('5', '1', '0', '', '');
INSERT INTO `luck_product_freight` VALUES ('6', '1', '0', '', '');
INSERT INTO `luck_product_freight` VALUES ('7', '1', '0', '', '');
INSERT INTO `luck_product_freight` VALUES ('8', '1', '0', '', '');
INSERT INTO `luck_product_freight` VALUES ('9', '1', '0', '', '');
INSERT INTO `luck_product_freight` VALUES ('10', '1', '0', '', '');

-- --------------------------------------------------------

-- ----------------------------
-- 表结构 `luck_product_image`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `luck_product_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL DEFAULT '0',
  `sku` varchar(200) NOT NULL DEFAULT '',
  `image` varchar(255) NOT NULL,
  `default` tinyint(3) NOT NULL DEFAULT '0',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=331 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- 转存表中的数据 luck_product_image
-- ----------------------------
INSERT INTO `luck_product_image` VALUES ('1', '1', '16143264906625', '/images/product/家用电器/0[luck1614326444luck].jpg', '1', '2021-02-26 16:01:29');
INSERT INTO `luck_product_image` VALUES ('2', '1', '16143264906625', '/images/product/家用电器/1[luck1614326444luck].jpg', '0', '2021-02-26 16:01:29');
INSERT INTO `luck_product_image` VALUES ('3', '1', '16143264906625', '/images/product/家用电器/2[luck1614326444luck].jpg', '0', '2021-02-26 16:01:29');
INSERT INTO `luck_product_image` VALUES ('4', '1', '16143264906625', '/images/product/家用电器/3[luck1614326444luck].jpg', '0', '2021-02-26 16:01:29');
INSERT INTO `luck_product_image` VALUES ('5', '1', '16143264906625', '/images/product/家用电器/4[luck1614326444luck].jpg', '0', '2021-02-26 16:01:29');
INSERT INTO `luck_product_image` VALUES ('6', '1', '16143264906625', '/images/product/家用电器/5[luck1614326444luck].jpg', '0', '2021-02-26 16:01:29');
INSERT INTO `luck_product_image` VALUES ('7', '2', '16143266740769', '/images/product/家用电器/0[luck1614326646luck].jpg', '1', '2021-02-26 16:04:33');
INSERT INTO `luck_product_image` VALUES ('8', '2', '16143266740769', '/images/product/家用电器/1[luck1614326646luck].jpg', '0', '2021-02-26 16:04:33');
INSERT INTO `luck_product_image` VALUES ('9', '2', '16143266740769', '/images/product/家用电器/2[luck1614326646luck].jpg', '0', '2021-02-26 16:04:33');
INSERT INTO `luck_product_image` VALUES ('10', '2', '16143266740769', '/images/product/家用电器/3[luck1614326646luck].jpg', '0', '2021-02-26 16:04:33');
INSERT INTO `luck_product_image` VALUES ('11', '2', '16143266740769', '/images/product/家用电器/4[luck1614326646luck].jpg', '0', '2021-02-26 16:04:33');
INSERT INTO `luck_product_image` VALUES ('12', '2', '16143266740769', '/images/product/家用电器/5[luck1614326646luck].jpg', '0', '2021-02-26 16:04:33');
INSERT INTO `luck_product_image` VALUES ('13', '3', '16143269153669', '/images/product/家用电器/0[luck1614326868luck].jpg', '1', '2021-02-26 16:08:34');
INSERT INTO `luck_product_image` VALUES ('14', '3', '16143269153669', '/images/product/家用电器/1[luck1614326868luck].jpg', '0', '2021-02-26 16:08:34');
INSERT INTO `luck_product_image` VALUES ('15', '3', '16143269153669', '/images/product/家用电器/2[luck1614326868luck].jpg', '0', '2021-02-26 16:08:34');
INSERT INTO `luck_product_image` VALUES ('16', '3', '16143269153669', '/images/product/家用电器/3[luck1614326868luck].jpg', '0', '2021-02-26 16:08:34');
INSERT INTO `luck_product_image` VALUES ('17', '3', '16143269153669', '/images/product/家用电器/4[luck1614326868luck].jpg', '0', '2021-02-26 16:08:34');
INSERT INTO `luck_product_image` VALUES ('18', '3', '16143269153669', '/images/product/家用电器/5[luck1614326868luck].jpg', '0', '2021-02-26 16:08:34');
INSERT INTO `luck_product_image` VALUES ('19', '4', '1614327337099', '/images/product/家用电器/0[luck1614327297luck].jpg', '1', '2021-02-26 16:15:36');
INSERT INTO `luck_product_image` VALUES ('20', '4', '1614327337099', '/images/product/家用电器/1[luck1614327297luck].jpg', '0', '2021-02-26 16:15:36');
INSERT INTO `luck_product_image` VALUES ('21', '4', '1614327337099', '/images/product/家用电器/2[luck1614327297luck].jpg', '0', '2021-02-26 16:15:36');
INSERT INTO `luck_product_image` VALUES ('22', '4', '1614327337099', '/images/product/家用电器/3[luck1614327297luck].jpg', '0', '2021-02-26 16:15:36');
INSERT INTO `luck_product_image` VALUES ('23', '4', '1614327337099', '/images/product/家用电器/4[luck1614327297luck].jpg', '0', '2021-02-26 16:15:36');
INSERT INTO `luck_product_image` VALUES ('24', '4', '1614327337099', '/images/product/家用电器/5[luck1614327297luck].jpg', '0', '2021-02-26 16:15:36');
INSERT INTO `luck_product_image` VALUES ('25', '5', '16143282350692', '/images/product/家用电器/云锦封面[luck1614330447luck].jpg', '1', '2021-02-26 17:09:34');
INSERT INTO `luck_product_image` VALUES ('26', '5', '16143282350692', '/images/product/家用电器/云锦封面[luck1614330447luck].jpg', '0', '2021-02-26 17:09:34');
INSERT INTO `luck_product_image` VALUES ('27', '5', '16143282350692', '/images/product/家用电器/1[luck1614330447luck].jpg', '0', '2021-02-26 17:09:34');
INSERT INTO `luck_product_image` VALUES ('28', '5', '16143282350692', '/images/product/家用电器/2[luck1614330447luck].jpg', '0', '2021-02-26 17:09:34');
INSERT INTO `luck_product_image` VALUES ('29', '5', '16143282350692', '/images/product/家用电器/3[luck1614330447luck].jpg', '0', '2021-02-26 17:09:34');
INSERT INTO `luck_product_image` VALUES ('30', '5', '16143282350692', '/images/product/家用电器/4[luck1614330447luck].jpg', '0', '2021-02-26 17:09:34');
INSERT INTO `luck_product_image` VALUES ('31', '5', '16143282351337', '/images/product/家用电器/品悦1封面[luck1614330447luck].jpg', '1', '2021-02-26 17:09:34');
INSERT INTO `luck_product_image` VALUES ('32', '5', '16143282351337', '/images/product/家用电器/品悦1封面[luck1614330447luck].jpg', '0', '2021-02-26 17:09:34');
INSERT INTO `luck_product_image` VALUES ('33', '5', '16143282351337', '/images/product/家用电器/1[luck1614330447luck].jpg', '0', '2021-02-26 17:09:34');
INSERT INTO `luck_product_image` VALUES ('34', '5', '16143282351337', '/images/product/家用电器/2[luck1614330447luck].jpg', '0', '2021-02-26 17:09:34');
INSERT INTO `luck_product_image` VALUES ('35', '5', '16143282351337', '/images/product/家用电器/3[luck1614330447luck].jpg', '0', '2021-02-26 17:09:34');
INSERT INTO `luck_product_image` VALUES ('36', '5', '16143282351337', '/images/product/家用电器/4[luck1614330447luck].jpg', '0', '2021-02-26 17:09:34');
INSERT INTO `luck_product_image` VALUES ('37', '5', '16143282351879', '/images/product/家用电器/品悦2封面[luck1614330447luck].jpg', '1', '2021-02-26 17:09:34');
INSERT INTO `luck_product_image` VALUES ('38', '5', '16143282351879', '/images/product/家用电器/品悦2封面[luck1614330447luck].jpg', '0', '2021-02-26 17:09:34');
INSERT INTO `luck_product_image` VALUES ('39', '5', '16143282351879', '/images/product/家用电器/1[luck1614330447luck].jpg', '0', '2021-02-26 17:09:34');
INSERT INTO `luck_product_image` VALUES ('40', '5', '16143282351879', '/images/product/家用电器/2[luck1614330447luck].jpg', '0', '2021-02-26 17:09:34');
INSERT INTO `luck_product_image` VALUES ('41', '5', '16143282351879', '/images/product/家用电器/3[luck1614330447luck].jpg', '0', '2021-02-26 17:09:34');
INSERT INTO `luck_product_image` VALUES ('42', '5', '16143282351879', '/images/product/家用电器/4[luck1614330447luck].jpg', '0', '2021-02-26 17:09:34');
INSERT INTO `luck_product_image` VALUES ('43', '5', '16143282352407', '/images/product/家用电器/云锦封面[luck1614330447luck].jpg', '1', '2021-02-26 17:09:34');
INSERT INTO `luck_product_image` VALUES ('44', '5', '16143282352407', '/images/product/家用电器/云锦封面[luck1614330447luck].jpg', '0', '2021-02-26 17:09:34');
INSERT INTO `luck_product_image` VALUES ('45', '5', '16143282352407', '/images/product/家用电器/1[luck1614330447luck].jpg', '0', '2021-02-26 17:09:34');
INSERT INTO `luck_product_image` VALUES ('46', '5', '16143282352407', '/images/product/家用电器/2[luck1614330447luck].jpg', '0', '2021-02-26 17:09:34');
INSERT INTO `luck_product_image` VALUES ('47', '5', '16143282352407', '/images/product/家用电器/3[luck1614330447luck].jpg', '0', '2021-02-26 17:09:34');
INSERT INTO `luck_product_image` VALUES ('48', '5', '16143282352407', '/images/product/家用电器/4[luck1614330447luck].jpg', '0', '2021-02-26 17:09:34');
INSERT INTO `luck_product_image` VALUES ('49', '5', '16143282352971', '/images/product/家用电器/品悦1封面[luck1614330447luck].jpg', '1', '2021-02-26 17:09:34');
INSERT INTO `luck_product_image` VALUES ('50', '5', '16143282352971', '/images/product/家用电器/品悦1封面[luck1614330447luck].jpg', '0', '2021-02-26 17:09:34');
INSERT INTO `luck_product_image` VALUES ('51', '5', '16143282352971', '/images/product/家用电器/1[luck1614330447luck].jpg', '0', '2021-02-26 17:09:34');
INSERT INTO `luck_product_image` VALUES ('52', '5', '16143282352971', '/images/product/家用电器/2[luck1614330447luck].jpg', '0', '2021-02-26 17:09:34');
INSERT INTO `luck_product_image` VALUES ('53', '5', '16143282352971', '/images/product/家用电器/3[luck1614330447luck].jpg', '0', '2021-02-26 17:09:34');
INSERT INTO `luck_product_image` VALUES ('54', '5', '16143282352971', '/images/product/家用电器/4[luck1614330447luck].jpg', '0', '2021-02-26 17:09:34');
INSERT INTO `luck_product_image` VALUES ('55', '5', '16143282353427', '/images/product/家用电器/品悦2封面[luck1614330447luck].jpg', '1', '2021-02-26 17:09:34');
INSERT INTO `luck_product_image` VALUES ('56', '5', '16143282353427', '/images/product/家用电器/品悦2封面[luck1614330447luck].jpg', '0', '2021-02-26 17:09:34');
INSERT INTO `luck_product_image` VALUES ('57', '5', '16143282353427', '/images/product/家用电器/1[luck1614330447luck].jpg', '0', '2021-02-26 17:09:34');
INSERT INTO `luck_product_image` VALUES ('58', '5', '16143282353427', '/images/product/家用电器/2[luck1614330447luck].jpg', '0', '2021-02-26 17:09:34');
INSERT INTO `luck_product_image` VALUES ('59', '5', '16143282353427', '/images/product/家用电器/3[luck1614330447luck].jpg', '0', '2021-02-26 17:09:34');
INSERT INTO `luck_product_image` VALUES ('60', '5', '16143282353427', '/images/product/家用电器/4[luck1614330447luck].jpg', '0', '2021-02-26 17:09:34');
INSERT INTO `luck_product_image` VALUES ('61', '5', '16143282350517', '/images/product/家用电器/云锦封面[luck1614330447luck].jpg', '1', '2021-02-26 17:09:35');
INSERT INTO `luck_product_image` VALUES ('62', '5', '16143282350517', '/images/product/家用电器/云锦封面[luck1614330447luck].jpg', '0', '2021-02-26 17:09:35');
INSERT INTO `luck_product_image` VALUES ('63', '5', '16143282350517', '/images/product/家用电器/1[luck1614330447luck].jpg', '0', '2021-02-26 17:09:35');
INSERT INTO `luck_product_image` VALUES ('64', '5', '16143282350517', '/images/product/家用电器/2[luck1614330447luck].jpg', '0', '2021-02-26 17:09:35');
INSERT INTO `luck_product_image` VALUES ('65', '5', '16143282350517', '/images/product/家用电器/3[luck1614330447luck].jpg', '0', '2021-02-26 17:09:35');
INSERT INTO `luck_product_image` VALUES ('66', '5', '16143282350517', '/images/product/家用电器/4[luck1614330447luck].jpg', '0', '2021-02-26 17:09:35');
INSERT INTO `luck_product_image` VALUES ('67', '6', '16143312343266', '/images/product/女装/0[luck1614331154luck].jpg', '1', '2021-02-26 17:20:34');
INSERT INTO `luck_product_image` VALUES ('68', '6', '16143312343266', '/images/product/女装/0[luck1614331154luck].jpg', '0', '2021-02-26 17:20:34');
INSERT INTO `luck_product_image` VALUES ('69', '6', '16143312343266', '/images/product/女装/2[luck1614331154luck].png', '0', '2021-02-26 17:20:34');
INSERT INTO `luck_product_image` VALUES ('70', '6', '16143312343266', '/images/product/女装/3[luck1614331154luck].png', '0', '2021-02-26 17:20:34');
INSERT INTO `luck_product_image` VALUES ('71', '6', '16143312343266', '/images/product/女装/4[luck1614331154luck].jpg', '0', '2021-02-26 17:20:34');
INSERT INTO `luck_product_image` VALUES ('72', '6', '16143312343266', '/images/product/女装/5[luck1614331154luck].jpg', '0', '2021-02-26 17:20:34');
INSERT INTO `luck_product_image` VALUES ('73', '6', '16143312357087', '/images/product/女装/0[luck1614331154luck].jpg', '1', '2021-02-26 17:20:34');
INSERT INTO `luck_product_image` VALUES ('74', '6', '16143312357087', '/images/product/女装/0[luck1614331154luck].jpg', '0', '2021-02-26 17:20:34');
INSERT INTO `luck_product_image` VALUES ('75', '6', '16143312357087', '/images/product/女装/2[luck1614331154luck].png', '0', '2021-02-26 17:20:34');
INSERT INTO `luck_product_image` VALUES ('76', '6', '16143312357087', '/images/product/女装/3[luck1614331154luck].png', '0', '2021-02-26 17:20:34');
INSERT INTO `luck_product_image` VALUES ('77', '6', '16143312357087', '/images/product/女装/4[luck1614331154luck].jpg', '0', '2021-02-26 17:20:34');
INSERT INTO `luck_product_image` VALUES ('78', '6', '16143312357087', '/images/product/女装/5[luck1614331154luck].jpg', '0', '2021-02-26 17:20:34');
INSERT INTO `luck_product_image` VALUES ('79', '6', '16143312358255', '/images/product/女装/0[luck1614331154luck].jpg', '1', '2021-02-26 17:20:34');
INSERT INTO `luck_product_image` VALUES ('80', '6', '16143312358255', '/images/product/女装/0[luck1614331154luck].jpg', '0', '2021-02-26 17:20:34');
INSERT INTO `luck_product_image` VALUES ('81', '6', '16143312358255', '/images/product/女装/2[luck1614331154luck].png', '0', '2021-02-26 17:20:34');
INSERT INTO `luck_product_image` VALUES ('82', '6', '16143312358255', '/images/product/女装/3[luck1614331154luck].png', '0', '2021-02-26 17:20:34');
INSERT INTO `luck_product_image` VALUES ('83', '6', '16143312358255', '/images/product/女装/4[luck1614331154luck].jpg', '0', '2021-02-26 17:20:34');
INSERT INTO `luck_product_image` VALUES ('84', '6', '16143312358255', '/images/product/女装/5[luck1614331154luck].jpg', '0', '2021-02-26 17:20:34');
INSERT INTO `luck_product_image` VALUES ('85', '6', '16143312359558', '/images/product/女装/0[luck1614331154luck].jpg', '1', '2021-02-26 17:20:34');
INSERT INTO `luck_product_image` VALUES ('86', '6', '16143312359558', '/images/product/女装/0[luck1614331154luck].jpg', '0', '2021-02-26 17:20:34');
INSERT INTO `luck_product_image` VALUES ('87', '6', '16143312359558', '/images/product/女装/2[luck1614331154luck].png', '0', '2021-02-26 17:20:34');
INSERT INTO `luck_product_image` VALUES ('88', '6', '16143312359558', '/images/product/女装/3[luck1614331154luck].png', '0', '2021-02-26 17:20:34');
INSERT INTO `luck_product_image` VALUES ('89', '6', '16143312359558', '/images/product/女装/4[luck1614331154luck].jpg', '0', '2021-02-26 17:20:34');
INSERT INTO `luck_product_image` VALUES ('90', '6', '16143312359558', '/images/product/女装/5[luck1614331154luck].jpg', '0', '2021-02-26 17:20:34');
INSERT INTO `luck_product_image` VALUES ('91', '6', '16143312360968', '/images/product/女装/0[luck1614331154luck].jpg', '1', '2021-02-26 17:20:34');
INSERT INTO `luck_product_image` VALUES ('92', '6', '16143312360968', '/images/product/女装/0[luck1614331154luck].jpg', '0', '2021-02-26 17:20:34');
INSERT INTO `luck_product_image` VALUES ('93', '6', '16143312360968', '/images/product/女装/2[luck1614331154luck].png', '0', '2021-02-26 17:20:34');
INSERT INTO `luck_product_image` VALUES ('94', '6', '16143312360968', '/images/product/女装/3[luck1614331154luck].png', '0', '2021-02-26 17:20:34');
INSERT INTO `luck_product_image` VALUES ('95', '6', '16143312360968', '/images/product/女装/4[luck1614331154luck].jpg', '0', '2021-02-26 17:20:34');
INSERT INTO `luck_product_image` VALUES ('96', '6', '16143312360968', '/images/product/女装/5[luck1614331154luck].jpg', '0', '2021-02-26 17:20:34');
INSERT INTO `luck_product_image` VALUES ('97', '6', '1614331236219', '/images/product/女装/0[luck1614331154luck].jpg', '1', '2021-02-26 17:20:35');
INSERT INTO `luck_product_image` VALUES ('98', '6', '1614331236219', '/images/product/女装/0[luck1614331154luck].jpg', '0', '2021-02-26 17:20:35');
INSERT INTO `luck_product_image` VALUES ('99', '6', '1614331236219', '/images/product/女装/2[luck1614331154luck].png', '0', '2021-02-26 17:20:35');
INSERT INTO `luck_product_image` VALUES ('100', '6', '1614331236219', '/images/product/女装/3[luck1614331154luck].png', '0', '2021-02-26 17:20:35');
INSERT INTO `luck_product_image` VALUES ('101', '6', '1614331236219', '/images/product/女装/4[luck1614331154luck].jpg', '0', '2021-02-26 17:20:35');
INSERT INTO `luck_product_image` VALUES ('102', '6', '1614331236219', '/images/product/女装/5[luck1614331154luck].jpg', '0', '2021-02-26 17:20:35');
INSERT INTO `luck_product_image` VALUES ('103', '6', '16143312342881', '/images/product/女装/0[luck1614331154luck].jpg', '1', '2021-02-26 17:20:35');
INSERT INTO `luck_product_image` VALUES ('104', '6', '16143312342881', '/images/product/女装/0[luck1614331154luck].jpg', '0', '2021-02-26 17:20:35');
INSERT INTO `luck_product_image` VALUES ('105', '6', '16143312342881', '/images/product/女装/2[luck1614331154luck].png', '0', '2021-02-26 17:20:35');
INSERT INTO `luck_product_image` VALUES ('106', '6', '16143312342881', '/images/product/女装/3[luck1614331154luck].png', '0', '2021-02-26 17:20:35');
INSERT INTO `luck_product_image` VALUES ('107', '6', '16143312342881', '/images/product/女装/4[luck1614331154luck].jpg', '0', '2021-02-26 17:20:35');
INSERT INTO `luck_product_image` VALUES ('108', '6', '16143312342881', '/images/product/女装/5[luck1614331154luck].jpg', '0', '2021-02-26 17:20:35');
INSERT INTO `luck_product_image` VALUES ('109', '7', '16143315466215', '/images/product/女装/0[luck1614331501luck].jpg', '1', '2021-02-26 17:25:45');
INSERT INTO `luck_product_image` VALUES ('110', '7', '16143315466215', '/images/product/女装/0[luck1614331501luck].jpg', '0', '2021-02-26 17:25:45');
INSERT INTO `luck_product_image` VALUES ('111', '7', '16143315466215', '/images/product/女装/2[luck1614331501luck].jpg', '0', '2021-02-26 17:25:45');
INSERT INTO `luck_product_image` VALUES ('112', '7', '16143315466215', '/images/product/女装/3[luck1614331501luck].jpg', '0', '2021-02-26 17:25:45');
INSERT INTO `luck_product_image` VALUES ('113', '7', '16143315466215', '/images/product/女装/4[luck1614331501luck].jpg', '0', '2021-02-26 17:25:45');
INSERT INTO `luck_product_image` VALUES ('114', '7', '16143315466215', '/images/product/女装/5[luck1614331501luck].jpg', '0', '2021-02-26 17:25:45');
INSERT INTO `luck_product_image` VALUES ('115', '7', '16143315471634', '/images/product/女装/0[luck1614331501luck].jpg', '1', '2021-02-26 17:25:45');
INSERT INTO `luck_product_image` VALUES ('116', '7', '16143315471634', '/images/product/女装/0[luck1614331501luck].jpg', '0', '2021-02-26 17:25:45');
INSERT INTO `luck_product_image` VALUES ('117', '7', '16143315471634', '/images/product/女装/2[luck1614331501luck].jpg', '0', '2021-02-26 17:25:45');
INSERT INTO `luck_product_image` VALUES ('118', '7', '16143315471634', '/images/product/女装/3[luck1614331501luck].jpg', '0', '2021-02-26 17:25:45');
INSERT INTO `luck_product_image` VALUES ('119', '7', '16143315471634', '/images/product/女装/4[luck1614331501luck].jpg', '0', '2021-02-26 17:25:45');
INSERT INTO `luck_product_image` VALUES ('120', '7', '16143315471634', '/images/product/女装/5[luck1614331501luck].jpg', '0', '2021-02-26 17:25:45');
INSERT INTO `luck_product_image` VALUES ('121', '7', '1614331547253', '/images/product/女装/0[luck1614331501luck].jpg', '1', '2021-02-26 17:25:46');
INSERT INTO `luck_product_image` VALUES ('122', '7', '1614331547253', '/images/product/女装/0[luck1614331501luck].jpg', '0', '2021-02-26 17:25:46');
INSERT INTO `luck_product_image` VALUES ('123', '7', '1614331547253', '/images/product/女装/2[luck1614331501luck].jpg', '0', '2021-02-26 17:25:46');
INSERT INTO `luck_product_image` VALUES ('124', '7', '1614331547253', '/images/product/女装/3[luck1614331501luck].jpg', '0', '2021-02-26 17:25:46');
INSERT INTO `luck_product_image` VALUES ('125', '7', '1614331547253', '/images/product/女装/4[luck1614331501luck].jpg', '0', '2021-02-26 17:25:46');
INSERT INTO `luck_product_image` VALUES ('126', '7', '1614331547253', '/images/product/女装/5[luck1614331501luck].jpg', '0', '2021-02-26 17:25:46');
INSERT INTO `luck_product_image` VALUES ('127', '7', '16143315465915', '/images/product/女装/0[luck1614331501luck].jpg', '1', '2021-02-26 17:25:46');
INSERT INTO `luck_product_image` VALUES ('128', '7', '16143315465915', '/images/product/女装/0[luck1614331501luck].jpg', '0', '2021-02-26 17:25:46');
INSERT INTO `luck_product_image` VALUES ('129', '7', '16143315465915', '/images/product/女装/2[luck1614331501luck].jpg', '0', '2021-02-26 17:25:46');
INSERT INTO `luck_product_image` VALUES ('130', '7', '16143315465915', '/images/product/女装/3[luck1614331501luck].jpg', '0', '2021-02-26 17:25:46');
INSERT INTO `luck_product_image` VALUES ('131', '7', '16143315465915', '/images/product/女装/4[luck1614331501luck].jpg', '0', '2021-02-26 17:25:46');
INSERT INTO `luck_product_image` VALUES ('132', '7', '16143315465915', '/images/product/女装/5[luck1614331501luck].jpg', '0', '2021-02-26 17:25:46');
INSERT INTO `luck_product_image` VALUES ('133', '8', '16143318904182', '/images/product/女装/0[luck1614331846luck].jpg', '1', '2021-02-26 17:31:29');
INSERT INTO `luck_product_image` VALUES ('134', '8', '16143318904182', '/images/product/女装/0[luck1614331846luck].jpg', '0', '2021-02-26 17:31:29');
INSERT INTO `luck_product_image` VALUES ('135', '8', '16143318904182', '/images/product/女装/2[luck1614331846luck].jpg', '0', '2021-02-26 17:31:29');
INSERT INTO `luck_product_image` VALUES ('136', '8', '16143318904182', '/images/product/女装/3[luck1614331846luck].jpg', '0', '2021-02-26 17:31:29');
INSERT INTO `luck_product_image` VALUES ('137', '8', '16143318904182', '/images/product/女装/4[luck1614331846luck].jpg', '0', '2021-02-26 17:31:29');
INSERT INTO `luck_product_image` VALUES ('138', '8', '16143318904182', '/images/product/女装/5[luck1614331846luck].jpg', '0', '2021-02-26 17:31:29');
INSERT INTO `luck_product_image` VALUES ('139', '8', '16143318910368', '/images/product/女装/0[luck1614331846luck].jpg', '1', '2021-02-26 17:31:29');
INSERT INTO `luck_product_image` VALUES ('140', '8', '16143318910368', '/images/product/女装/0[luck1614331846luck].jpg', '0', '2021-02-26 17:31:29');
INSERT INTO `luck_product_image` VALUES ('141', '8', '16143318910368', '/images/product/女装/2[luck1614331846luck].jpg', '0', '2021-02-26 17:31:29');
INSERT INTO `luck_product_image` VALUES ('142', '8', '16143318910368', '/images/product/女装/3[luck1614331846luck].jpg', '0', '2021-02-26 17:31:29');
INSERT INTO `luck_product_image` VALUES ('143', '8', '16143318910368', '/images/product/女装/4[luck1614331846luck].jpg', '0', '2021-02-26 17:31:29');
INSERT INTO `luck_product_image` VALUES ('144', '8', '16143318910368', '/images/product/女装/5[luck1614331846luck].jpg', '0', '2021-02-26 17:31:29');
INSERT INTO `luck_product_image` VALUES ('145', '8', '1614331891143', '/images/product/女装/0[luck1614331846luck].jpg', '1', '2021-02-26 17:31:29');
INSERT INTO `luck_product_image` VALUES ('146', '8', '1614331891143', '/images/product/女装/0[luck1614331846luck].jpg', '0', '2021-02-26 17:31:29');
INSERT INTO `luck_product_image` VALUES ('147', '8', '1614331891143', '/images/product/女装/2[luck1614331846luck].jpg', '0', '2021-02-26 17:31:29');
INSERT INTO `luck_product_image` VALUES ('148', '8', '1614331891143', '/images/product/女装/3[luck1614331846luck].jpg', '0', '2021-02-26 17:31:29');
INSERT INTO `luck_product_image` VALUES ('149', '8', '1614331891143', '/images/product/女装/4[luck1614331846luck].jpg', '0', '2021-02-26 17:31:29');
INSERT INTO `luck_product_image` VALUES ('150', '8', '1614331891143', '/images/product/女装/5[luck1614331846luck].jpg', '0', '2021-02-26 17:31:29');
INSERT INTO `luck_product_image` VALUES ('151', '8', '16143318912482', '/images/product/女装/0[luck1614331846luck].jpg', '1', '2021-02-26 17:31:30');
INSERT INTO `luck_product_image` VALUES ('152', '8', '16143318912482', '/images/product/女装/0[luck1614331846luck].jpg', '0', '2021-02-26 17:31:30');
INSERT INTO `luck_product_image` VALUES ('153', '8', '16143318912482', '/images/product/女装/2[luck1614331846luck].jpg', '0', '2021-02-26 17:31:30');
INSERT INTO `luck_product_image` VALUES ('154', '8', '16143318912482', '/images/product/女装/3[luck1614331846luck].jpg', '0', '2021-02-26 17:31:30');
INSERT INTO `luck_product_image` VALUES ('155', '8', '16143318912482', '/images/product/女装/4[luck1614331846luck].jpg', '0', '2021-02-26 17:31:30');
INSERT INTO `luck_product_image` VALUES ('156', '8', '16143318912482', '/images/product/女装/5[luck1614331846luck].jpg', '0', '2021-02-26 17:31:30');
INSERT INTO `luck_product_image` VALUES ('157', '8', '16143318903781', '/images/product/女装/0[luck1614331846luck].jpg', '1', '2021-02-26 17:31:30');
INSERT INTO `luck_product_image` VALUES ('158', '8', '16143318903781', '/images/product/女装/0[luck1614331846luck].jpg', '0', '2021-02-26 17:31:30');
INSERT INTO `luck_product_image` VALUES ('159', '8', '16143318903781', '/images/product/女装/2[luck1614331846luck].jpg', '0', '2021-02-26 17:31:30');
INSERT INTO `luck_product_image` VALUES ('160', '8', '16143318903781', '/images/product/女装/3[luck1614331846luck].jpg', '0', '2021-02-26 17:31:30');
INSERT INTO `luck_product_image` VALUES ('161', '8', '16143318903781', '/images/product/女装/4[luck1614331846luck].jpg', '0', '2021-02-26 17:31:30');
INSERT INTO `luck_product_image` VALUES ('162', '8', '16143318903781', '/images/product/女装/5[luck1614331846luck].jpg', '0', '2021-02-26 17:31:30');
INSERT INTO `luck_product_image` VALUES ('163', '9', '16143323329358', '/images/product/女装/0[luck1614332279luck].jpg', '1', '2021-02-26 17:38:52');
INSERT INTO `luck_product_image` VALUES ('164', '9', '16143323329358', '/images/product/女装/1[luck1614332279luck].jpg', '0', '2021-02-26 17:38:52');
INSERT INTO `luck_product_image` VALUES ('165', '9', '16143323329358', '/images/product/女装/2[luck1614332279luck].jpg', '0', '2021-02-26 17:38:52');
INSERT INTO `luck_product_image` VALUES ('166', '9', '16143323329358', '/images/product/女装/3[luck1614332279luck].jpg', '0', '2021-02-26 17:38:52');
INSERT INTO `luck_product_image` VALUES ('167', '9', '16143323329358', '/images/product/女装/4[luck1614332279luck].jpg', '0', '2021-02-26 17:38:52');
INSERT INTO `luck_product_image` VALUES ('168', '9', '16143323329358', '/images/product/女装/5[luck1614332279luck].jpg', '0', '2021-02-26 17:38:52');
INSERT INTO `luck_product_image` VALUES ('169', '9', '16143323335476', '/images/product/女装/0[luck1614332279luck].jpg', '1', '2021-02-26 17:38:52');
INSERT INTO `luck_product_image` VALUES ('170', '9', '16143323335476', '/images/product/女装/1[luck1614332279luck].jpg', '0', '2021-02-26 17:38:52');
INSERT INTO `luck_product_image` VALUES ('171', '9', '16143323335476', '/images/product/女装/2[luck1614332279luck].jpg', '0', '2021-02-26 17:38:52');
INSERT INTO `luck_product_image` VALUES ('172', '9', '16143323335476', '/images/product/女装/3[luck1614332279luck].jpg', '0', '2021-02-26 17:38:52');
INSERT INTO `luck_product_image` VALUES ('173', '9', '16143323335476', '/images/product/女装/4[luck1614332279luck].jpg', '0', '2021-02-26 17:38:52');
INSERT INTO `luck_product_image` VALUES ('174', '9', '16143323335476', '/images/product/女装/5[luck1614332279luck].jpg', '0', '2021-02-26 17:38:52');
INSERT INTO `luck_product_image` VALUES ('175', '9', '16143323336234', '/images/product/女装/0[luck1614332279luck].jpg', '1', '2021-02-26 17:38:52');
INSERT INTO `luck_product_image` VALUES ('176', '9', '16143323336234', '/images/product/女装/1[luck1614332279luck].jpg', '0', '2021-02-26 17:38:52');
INSERT INTO `luck_product_image` VALUES ('177', '9', '16143323336234', '/images/product/女装/2[luck1614332279luck].jpg', '0', '2021-02-26 17:38:52');
INSERT INTO `luck_product_image` VALUES ('178', '9', '16143323336234', '/images/product/女装/3[luck1614332279luck].jpg', '0', '2021-02-26 17:38:52');
INSERT INTO `luck_product_image` VALUES ('179', '9', '16143323336234', '/images/product/女装/4[luck1614332279luck].jpg', '0', '2021-02-26 17:38:52');
INSERT INTO `luck_product_image` VALUES ('180', '9', '16143323336234', '/images/product/女装/5[luck1614332279luck].jpg', '0', '2021-02-26 17:38:52');
INSERT INTO `luck_product_image` VALUES ('181', '9', '16143323336927', '/images/product/女装/0[luck1614332279luck].jpg', '1', '2021-02-26 17:38:52');
INSERT INTO `luck_product_image` VALUES ('182', '9', '16143323336927', '/images/product/女装/1[luck1614332279luck].jpg', '0', '2021-02-26 17:38:52');
INSERT INTO `luck_product_image` VALUES ('183', '9', '16143323336927', '/images/product/女装/2[luck1614332279luck].jpg', '0', '2021-02-26 17:38:52');
INSERT INTO `luck_product_image` VALUES ('184', '9', '16143323336927', '/images/product/女装/3[luck1614332279luck].jpg', '0', '2021-02-26 17:38:52');
INSERT INTO `luck_product_image` VALUES ('185', '9', '16143323336927', '/images/product/女装/4[luck1614332279luck].jpg', '0', '2021-02-26 17:38:52');
INSERT INTO `luck_product_image` VALUES ('186', '9', '16143323336927', '/images/product/女装/5[luck1614332279luck].jpg', '0', '2021-02-26 17:38:52');
INSERT INTO `luck_product_image` VALUES ('187', '9', '16143323337658', '/images/product/女装/0[luck1614332279luck].jpg', '1', '2021-02-26 17:38:52');
INSERT INTO `luck_product_image` VALUES ('188', '9', '16143323337658', '/images/product/女装/1[luck1614332279luck].jpg', '0', '2021-02-26 17:38:52');
INSERT INTO `luck_product_image` VALUES ('189', '9', '16143323337658', '/images/product/女装/2[luck1614332279luck].jpg', '0', '2021-02-26 17:38:52');
INSERT INTO `luck_product_image` VALUES ('190', '9', '16143323337658', '/images/product/女装/3[luck1614332279luck].jpg', '0', '2021-02-26 17:38:52');
INSERT INTO `luck_product_image` VALUES ('191', '9', '16143323337658', '/images/product/女装/4[luck1614332279luck].jpg', '0', '2021-02-26 17:38:52');
INSERT INTO `luck_product_image` VALUES ('192', '9', '16143323337658', '/images/product/女装/5[luck1614332279luck].jpg', '0', '2021-02-26 17:38:52');
INSERT INTO `luck_product_image` VALUES ('193', '9', '16143323338338', '/images/product/女装/0[luck1614332279luck].jpg', '1', '2021-02-26 17:38:52');
INSERT INTO `luck_product_image` VALUES ('194', '9', '16143323338338', '/images/product/女装/1[luck1614332279luck].jpg', '0', '2021-02-26 17:38:52');
INSERT INTO `luck_product_image` VALUES ('195', '9', '16143323338338', '/images/product/女装/2[luck1614332279luck].jpg', '0', '2021-02-26 17:38:52');
INSERT INTO `luck_product_image` VALUES ('196', '9', '16143323338338', '/images/product/女装/3[luck1614332279luck].jpg', '0', '2021-02-26 17:38:52');
INSERT INTO `luck_product_image` VALUES ('197', '9', '16143323338338', '/images/product/女装/4[luck1614332279luck].jpg', '0', '2021-02-26 17:38:52');
INSERT INTO `luck_product_image` VALUES ('198', '9', '16143323338338', '/images/product/女装/5[luck1614332279luck].jpg', '0', '2021-02-26 17:38:52');
INSERT INTO `luck_product_image` VALUES ('199', '9', '16143323329136', '/images/product/女装/0[luck1614332279luck].jpg', '1', '2021-02-26 17:38:52');
INSERT INTO `luck_product_image` VALUES ('200', '9', '16143323329136', '/images/product/女装/1[luck1614332279luck].jpg', '0', '2021-02-26 17:38:52');
INSERT INTO `luck_product_image` VALUES ('201', '9', '16143323329136', '/images/product/女装/2[luck1614332279luck].jpg', '0', '2021-02-26 17:38:52');
INSERT INTO `luck_product_image` VALUES ('202', '9', '16143323329136', '/images/product/女装/3[luck1614332279luck].jpg', '0', '2021-02-26 17:38:52');
INSERT INTO `luck_product_image` VALUES ('203', '9', '16143323329136', '/images/product/女装/4[luck1614332279luck].jpg', '0', '2021-02-26 17:38:52');
INSERT INTO `luck_product_image` VALUES ('204', '9', '16143323329136', '/images/product/女装/5[luck1614332279luck].jpg', '0', '2021-02-26 17:38:52');
INSERT INTO `luck_product_image` VALUES ('205', '10', '16143329363384', '/images/product/女装/白色封面[luck1614332958luck].jpg', '1', '2021-02-26 17:50:24');
INSERT INTO `luck_product_image` VALUES ('206', '10', '16143329363384', '/images/product/女装/白色封面[luck1614332958luck].jpg', '0', '2021-02-26 17:50:24');
INSERT INTO `luck_product_image` VALUES ('207', '10', '16143329363384', '/images/product/女装/蓝色封面[luck1614332958luck].jpg', '0', '2021-02-26 17:50:24');
INSERT INTO `luck_product_image` VALUES ('208', '10', '16143329363384', '/images/product/女装/绿色封面[luck1614332958luck].jpg', '0', '2021-02-26 17:50:24');
INSERT INTO `luck_product_image` VALUES ('209', '10', '16143329363384', '/images/product/女装/黄色封面[luck1614332958luck].jpg', '0', '2021-02-26 17:50:24');
INSERT INTO `luck_product_image` VALUES ('210', '10', '16143329363384', '/images/product/女装/红色封面[luck1614332958luck].jpg', '0', '2021-02-26 17:50:24');
INSERT INTO `luck_product_image` VALUES ('211', '10', '16143329363606', '/images/product/女装/白色封面[luck1614332958luck].jpg', '1', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('212', '10', '16143329363606', '/images/product/女装/白色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('213', '10', '16143329363606', '/images/product/女装/蓝色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('214', '10', '16143329363606', '/images/product/女装/绿色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('215', '10', '16143329363606', '/images/product/女装/黄色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('216', '10', '16143329363606', '/images/product/女装/红色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('217', '10', '16143329364618', '/images/product/女装/蓝色封面[luck1614332958luck].jpg', '1', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('218', '10', '16143329364618', '/images/product/女装/蓝色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('219', '10', '16143329364618', '/images/product/女装/蓝色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('220', '10', '16143329364618', '/images/product/女装/绿色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('221', '10', '16143329364618', '/images/product/女装/黄色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('222', '10', '16143329364618', '/images/product/女装/红色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('223', '10', '16143329365375', '/images/product/女装/绿色封面[luck1614332958luck].jpg', '1', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('224', '10', '16143329365375', '/images/product/女装/绿色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('225', '10', '16143329365375', '/images/product/女装/蓝色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('226', '10', '16143329365375', '/images/product/女装/绿色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('227', '10', '16143329365375', '/images/product/女装/黄色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('228', '10', '16143329365375', '/images/product/女装/红色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('229', '10', '16143329366117', '/images/product/女装/黄色封面[luck1614332958luck].jpg', '1', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('230', '10', '16143329366117', '/images/product/女装/黄色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('231', '10', '16143329366117', '/images/product/女装/蓝色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('232', '10', '16143329366117', '/images/product/女装/绿色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('233', '10', '16143329366117', '/images/product/女装/黄色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('234', '10', '16143329366117', '/images/product/女装/红色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('235', '10', '16143329366847', '/images/product/女装/红色封面[luck1614332958luck].jpg', '1', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('236', '10', '16143329366847', '/images/product/女装/红色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('237', '10', '16143329366847', '/images/product/女装/蓝色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('238', '10', '16143329366847', '/images/product/女装/绿色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('239', '10', '16143329366847', '/images/product/女装/黄色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('240', '10', '16143329366847', '/images/product/女装/红色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('241', '10', '16143329367576', '/images/product/女装/白色封面[luck1614332958luck].jpg', '1', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('242', '10', '16143329367576', '/images/product/女装/白色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('243', '10', '16143329367576', '/images/product/女装/蓝色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('244', '10', '16143329367576', '/images/product/女装/绿色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('245', '10', '16143329367576', '/images/product/女装/黄色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('246', '10', '16143329367576', '/images/product/女装/红色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('247', '10', '16143329368295', '/images/product/女装/蓝色封面[luck1614332958luck].jpg', '1', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('248', '10', '16143329368295', '/images/product/女装/蓝色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('249', '10', '16143329368295', '/images/product/女装/蓝色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('250', '10', '16143329368295', '/images/product/女装/绿色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('251', '10', '16143329368295', '/images/product/女装/黄色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('252', '10', '16143329368295', '/images/product/女装/红色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('253', '10', '16143329369045', '/images/product/女装/绿色封面[luck1614332958luck].jpg', '1', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('254', '10', '16143329369045', '/images/product/女装/绿色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('255', '10', '16143329369045', '/images/product/女装/蓝色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('256', '10', '16143329369045', '/images/product/女装/绿色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('257', '10', '16143329369045', '/images/product/女装/黄色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('258', '10', '16143329369045', '/images/product/女装/红色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('259', '10', '16143329369784', '/images/product/女装/黄色封面[luck1614332958luck].jpg', '1', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('260', '10', '16143329369784', '/images/product/女装/黄色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('261', '10', '16143329369784', '/images/product/女装/蓝色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('262', '10', '16143329369784', '/images/product/女装/绿色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('263', '10', '16143329369784', '/images/product/女装/黄色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('264', '10', '16143329369784', '/images/product/女装/红色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('265', '10', '16143329370584', '/images/product/女装/红色封面[luck1614332958luck].jpg', '1', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('266', '10', '16143329370584', '/images/product/女装/红色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('267', '10', '16143329370584', '/images/product/女装/蓝色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('268', '10', '16143329370584', '/images/product/女装/绿色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('269', '10', '16143329370584', '/images/product/女装/黄色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('270', '10', '16143329370584', '/images/product/女装/红色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('271', '10', '16143329371324', '/images/product/女装/白色封面[luck1614332958luck].jpg', '1', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('272', '10', '16143329371324', '/images/product/女装/白色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('273', '10', '16143329371324', '/images/product/女装/蓝色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('274', '10', '16143329371324', '/images/product/女装/绿色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('275', '10', '16143329371324', '/images/product/女装/黄色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('276', '10', '16143329371324', '/images/product/女装/红色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('277', '10', '16143329372053', '/images/product/女装/蓝色封面[luck1614332958luck].jpg', '1', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('278', '10', '16143329372053', '/images/product/女装/蓝色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('279', '10', '16143329372053', '/images/product/女装/蓝色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('280', '10', '16143329372053', '/images/product/女装/绿色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('281', '10', '16143329372053', '/images/product/女装/黄色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('282', '10', '16143329372053', '/images/product/女装/红色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:23');
INSERT INTO `luck_product_image` VALUES ('283', '10', '16143329372796', '/images/product/女装/绿色封面[luck1614332958luck].jpg', '1', '2021-02-27 21:22:24');
INSERT INTO `luck_product_image` VALUES ('284', '10', '16143329372796', '/images/product/女装/绿色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:24');
INSERT INTO `luck_product_image` VALUES ('285', '10', '16143329372796', '/images/product/女装/蓝色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:24');
INSERT INTO `luck_product_image` VALUES ('286', '10', '16143329372796', '/images/product/女装/绿色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:24');
INSERT INTO `luck_product_image` VALUES ('287', '10', '16143329372796', '/images/product/女装/黄色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:24');
INSERT INTO `luck_product_image` VALUES ('288', '10', '16143329372796', '/images/product/女装/红色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:24');
INSERT INTO `luck_product_image` VALUES ('289', '10', '16143329373584', '/images/product/女装/黄色封面[luck1614332958luck].jpg', '1', '2021-02-27 21:22:24');
INSERT INTO `luck_product_image` VALUES ('290', '10', '16143329373584', '/images/product/女装/黄色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:24');
INSERT INTO `luck_product_image` VALUES ('291', '10', '16143329373584', '/images/product/女装/蓝色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:24');
INSERT INTO `luck_product_image` VALUES ('292', '10', '16143329373584', '/images/product/女装/绿色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:24');
INSERT INTO `luck_product_image` VALUES ('293', '10', '16143329373584', '/images/product/女装/黄色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:24');
INSERT INTO `luck_product_image` VALUES ('294', '10', '16143329373584', '/images/product/女装/红色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:24');
INSERT INTO `luck_product_image` VALUES ('295', '10', '16143329374334', '/images/product/女装/红色封面[luck1614332958luck].jpg', '1', '2021-02-27 21:22:24');
INSERT INTO `luck_product_image` VALUES ('296', '10', '16143329374334', '/images/product/女装/红色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:24');
INSERT INTO `luck_product_image` VALUES ('297', '10', '16143329374334', '/images/product/女装/蓝色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:24');
INSERT INTO `luck_product_image` VALUES ('298', '10', '16143329374334', '/images/product/女装/绿色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:24');
INSERT INTO `luck_product_image` VALUES ('299', '10', '16143329374334', '/images/product/女装/黄色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:24');
INSERT INTO `luck_product_image` VALUES ('300', '10', '16143329374334', '/images/product/女装/红色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:24');
INSERT INTO `luck_product_image` VALUES ('301', '10', '1614332937523', '/images/product/女装/白色封面[luck1614332958luck].jpg', '1', '2021-02-27 21:22:24');
INSERT INTO `luck_product_image` VALUES ('302', '10', '1614332937523', '/images/product/女装/白色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:24');
INSERT INTO `luck_product_image` VALUES ('303', '10', '1614332937523', '/images/product/女装/蓝色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:24');
INSERT INTO `luck_product_image` VALUES ('304', '10', '1614332937523', '/images/product/女装/绿色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:24');
INSERT INTO `luck_product_image` VALUES ('305', '10', '1614332937523', '/images/product/女装/黄色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:24');
INSERT INTO `luck_product_image` VALUES ('306', '10', '1614332937523', '/images/product/女装/红色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:24');
INSERT INTO `luck_product_image` VALUES ('307', '10', '16143329375973', '/images/product/女装/蓝色封面[luck1614332958luck].jpg', '1', '2021-02-27 21:22:24');
INSERT INTO `luck_product_image` VALUES ('308', '10', '16143329375973', '/images/product/女装/蓝色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:24');
INSERT INTO `luck_product_image` VALUES ('309', '10', '16143329375973', '/images/product/女装/蓝色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:24');
INSERT INTO `luck_product_image` VALUES ('310', '10', '16143329375973', '/images/product/女装/绿色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:24');
INSERT INTO `luck_product_image` VALUES ('311', '10', '16143329375973', '/images/product/女装/黄色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:24');
INSERT INTO `luck_product_image` VALUES ('312', '10', '16143329375973', '/images/product/女装/红色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:24');
INSERT INTO `luck_product_image` VALUES ('313', '10', '16143329376657', '/images/product/女装/绿色封面[luck1614332958luck].jpg', '1', '2021-02-27 21:22:24');
INSERT INTO `luck_product_image` VALUES ('314', '10', '16143329376657', '/images/product/女装/绿色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:24');
INSERT INTO `luck_product_image` VALUES ('315', '10', '16143329376657', '/images/product/女装/蓝色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:24');
INSERT INTO `luck_product_image` VALUES ('316', '10', '16143329376657', '/images/product/女装/绿色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:24');
INSERT INTO `luck_product_image` VALUES ('317', '10', '16143329376657', '/images/product/女装/黄色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:24');
INSERT INTO `luck_product_image` VALUES ('318', '10', '16143329376657', '/images/product/女装/红色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:24');
INSERT INTO `luck_product_image` VALUES ('319', '10', '16143329377422', '/images/product/女装/黄色封面[luck1614332958luck].jpg', '1', '2021-02-27 21:22:24');
INSERT INTO `luck_product_image` VALUES ('320', '10', '16143329377422', '/images/product/女装/黄色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:24');
INSERT INTO `luck_product_image` VALUES ('321', '10', '16143329377422', '/images/product/女装/蓝色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:24');
INSERT INTO `luck_product_image` VALUES ('322', '10', '16143329377422', '/images/product/女装/绿色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:24');
INSERT INTO `luck_product_image` VALUES ('323', '10', '16143329377422', '/images/product/女装/黄色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:24');
INSERT INTO `luck_product_image` VALUES ('324', '10', '16143329377422', '/images/product/女装/红色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:24');
INSERT INTO `luck_product_image` VALUES ('325', '10', '16143329378212', '/images/product/女装/红色封面[luck1614332958luck].jpg', '1', '2021-02-27 21:22:24');
INSERT INTO `luck_product_image` VALUES ('326', '10', '16143329378212', '/images/product/女装/红色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:24');
INSERT INTO `luck_product_image` VALUES ('327', '10', '16143329378212', '/images/product/女装/蓝色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:24');
INSERT INTO `luck_product_image` VALUES ('328', '10', '16143329378212', '/images/product/女装/绿色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:24');
INSERT INTO `luck_product_image` VALUES ('329', '10', '16143329378212', '/images/product/女装/黄色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:24');
INSERT INTO `luck_product_image` VALUES ('330', '10', '16143329378212', '/images/product/女装/红色封面[luck1614332958luck].jpg', '0', '2021-02-27 21:22:24');

-- --------------------------------------------------------

-- ----------------------------
-- 表结构 `luck_product_model`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `luck_product_model` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '1=Enabled 0=Disabled 99=Delete',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='产品模型';

-- ----------------------------
-- 转存表中的数据 luck_product_model
-- ----------------------------
INSERT INTO `luck_product_model` VALUES ('1', '空调_格力', '格力空调系列使用', '1');
INSERT INTO `luck_product_model` VALUES ('2', '服装', '服装系列产品使用', '1');

-- --------------------------------------------------------

-- ----------------------------
-- 表结构 `luck_product_sku`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `luck_product_sku` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL DEFAULT '0',
  `sku` varchar(200) NOT NULL DEFAULT '',
  `stock` mediumint(8) NOT NULL DEFAULT '0',
  `original_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `sale_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `default` tinyint(3) NOT NULL DEFAULT '0' COMMENT '1=spu 0=sku',
  `sort` mediumint(8) NOT NULL DEFAULT '0',
  `sale` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '1=Enabled 0=Disabled',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COMMENT='产品SKU';

-- ----------------------------
-- 转存表中的数据 luck_product_sku
-- ----------------------------
INSERT INTO `luck_product_sku` VALUES ('1', '1', '16143264906625', '5000', '0.00', '999.00', '1', '0', '0', '1');
INSERT INTO `luck_product_sku` VALUES ('2', '2', '16143266740769', '5000', '0.00', '1599.00', '1', '0', '0', '1');
INSERT INTO `luck_product_sku` VALUES ('3', '3', '16143269153669', '2000', '0.00', '2799.00', '1', '0', '0', '1');
INSERT INTO `luck_product_sku` VALUES ('4', '4', '1614327337099', '5000', '0.00', '1899.00', '1', '0', '0', '1');
INSERT INTO `luck_product_sku` VALUES ('5', '5', '16143282350692', '2000', '0.00', '2699.00', '0', '99', '0', '1');
INSERT INTO `luck_product_sku` VALUES ('6', '5', '16143282351337', '2000', '0.00', '2699.00', '0', '98', '0', '1');
INSERT INTO `luck_product_sku` VALUES ('7', '5', '16143282351879', '2000', '0.00', '2699.00', '0', '97', '0', '1');
INSERT INTO `luck_product_sku` VALUES ('8', '5', '16143282352407', '2000', '0.00', '3099.00', '0', '96', '0', '1');
INSERT INTO `luck_product_sku` VALUES ('9', '5', '16143282352971', '2000', '0.00', '2899.00', '0', '95', '0', '1');
INSERT INTO `luck_product_sku` VALUES ('10', '5', '16143282353427', '2000', '0.00', '2899.00', '0', '0', '0', '1');
INSERT INTO `luck_product_sku` VALUES ('11', '5', '16143282350517', '2000', '0.00', '2699.00', '1', '0', '0', '0');
INSERT INTO `luck_product_sku` VALUES ('12', '6', '16143312343266', '6000', '0.00', '99.00', '0', '0', '0', '1');
INSERT INTO `luck_product_sku` VALUES ('13', '6', '16143312357087', '6000', '0.00', '99.00', '0', '0', '0', '1');
INSERT INTO `luck_product_sku` VALUES ('14', '6', '16143312358255', '6000', '0.00', '99.00', '0', '97', '0', '1');
INSERT INTO `luck_product_sku` VALUES ('15', '6', '16143312359558', '6000', '0.00', '99.00', '0', '99', '0', '1');
INSERT INTO `luck_product_sku` VALUES ('16', '6', '16143312360968', '6000', '0.00', '99.00', '0', '98', '0', '1');
INSERT INTO `luck_product_sku` VALUES ('17', '6', '1614331236219', '6000', '0.00', '99.00', '0', '0', '0', '1');
INSERT INTO `luck_product_sku` VALUES ('18', '6', '16143312342881', '6000', '0.00', '99.00', '1', '0', '0', '0');
INSERT INTO `luck_product_sku` VALUES ('19', '7', '16143315466215', '2000', '0.00', '146.00', '0', '0', '0', '1');
INSERT INTO `luck_product_sku` VALUES ('20', '7', '16143315471634', '2000', '0.00', '146.00', '0', '0', '0', '1');
INSERT INTO `luck_product_sku` VALUES ('21', '7', '1614331547253', '2000', '0.00', '146.00', '0', '0', '0', '1');
INSERT INTO `luck_product_sku` VALUES ('22', '7', '16143315465915', '2000', '0.00', '146.00', '1', '0', '0', '0');
INSERT INTO `luck_product_sku` VALUES ('23', '8', '16143318904182', '5000', '0.00', '188.00', '0', '0', '0', '1');
INSERT INTO `luck_product_sku` VALUES ('24', '8', '16143318910368', '5000', '0.00', '188.00', '0', '0', '0', '1');
INSERT INTO `luck_product_sku` VALUES ('25', '8', '1614331891143', '5000', '0.00', '188.00', '0', '0', '0', '1');
INSERT INTO `luck_product_sku` VALUES ('26', '8', '16143318912482', '5000', '0.00', '188.00', '0', '0', '0', '1');
INSERT INTO `luck_product_sku` VALUES ('27', '8', '16143318903781', '5000', '0.00', '188.00', '1', '0', '0', '0');
INSERT INTO `luck_product_sku` VALUES ('28', '9', '16143323329358', '1000', '0.00', '268.00', '0', '0', '0', '1');
INSERT INTO `luck_product_sku` VALUES ('29', '9', '16143323335476', '1000', '0.00', '268.00', '0', '0', '0', '1');
INSERT INTO `luck_product_sku` VALUES ('30', '9', '16143323336234', '1000', '0.00', '268.00', '0', '0', '0', '1');
INSERT INTO `luck_product_sku` VALUES ('31', '9', '16143323336927', '1000', '0.00', '268.00', '0', '0', '0', '1');
INSERT INTO `luck_product_sku` VALUES ('32', '9', '16143323337658', '1000', '0.00', '268.00', '0', '0', '0', '1');
INSERT INTO `luck_product_sku` VALUES ('33', '9', '16143323338338', '1000', '0.00', '268.00', '0', '0', '0', '1');
INSERT INTO `luck_product_sku` VALUES ('34', '9', '16143323329136', '1000', '0.00', '268.00', '1', '0', '0', '0');
INSERT INTO `luck_product_sku` VALUES ('35', '10', '16143329363606', '1500', '0.00', '79.00', '0', '0', '0', '1');
INSERT INTO `luck_product_sku` VALUES ('36', '10', '16143329364618', '1500', '0.00', '79.00', '0', '0', '0', '1');
INSERT INTO `luck_product_sku` VALUES ('37', '10', '16143329365375', '1500', '0.00', '79.00', '0', '0', '0', '1');
INSERT INTO `luck_product_sku` VALUES ('38', '10', '16143329366117', '1500', '0.00', '79.00', '0', '0', '0', '1');
INSERT INTO `luck_product_sku` VALUES ('39', '10', '16143329366847', '1500', '0.00', '79.00', '0', '0', '0', '1');
INSERT INTO `luck_product_sku` VALUES ('40', '10', '16143329367576', '1500', '0.00', '79.00', '0', '0', '0', '1');
INSERT INTO `luck_product_sku` VALUES ('41', '10', '16143329368295', '1500', '0.00', '79.00', '0', '0', '0', '1');
INSERT INTO `luck_product_sku` VALUES ('42', '10', '16143329369045', '1500', '0.00', '79.00', '0', '0', '0', '1');
INSERT INTO `luck_product_sku` VALUES ('43', '10', '16143329369784', '1500', '0.00', '79.00', '0', '0', '0', '1');
INSERT INTO `luck_product_sku` VALUES ('44', '10', '16143329370584', '1500', '0.00', '79.00', '0', '0', '0', '1');
INSERT INTO `luck_product_sku` VALUES ('45', '10', '16143329371324', '1500', '0.00', '79.00', '0', '99', '0', '1');
INSERT INTO `luck_product_sku` VALUES ('46', '10', '16143329372053', '1500', '0.00', '79.00', '0', '98', '0', '1');
INSERT INTO `luck_product_sku` VALUES ('47', '10', '16143329372796', '1500', '0.00', '79.00', '0', '97', '0', '1');
INSERT INTO `luck_product_sku` VALUES ('48', '10', '16143329373584', '1500', '0.00', '79.00', '0', '96', '0', '1');
INSERT INTO `luck_product_sku` VALUES ('49', '10', '16143329374334', '1500', '0.00', '79.00', '0', '95', '0', '1');
INSERT INTO `luck_product_sku` VALUES ('50', '10', '1614332937523', '1500', '0.00', '79.00', '0', '0', '0', '1');
INSERT INTO `luck_product_sku` VALUES ('51', '10', '16143329375973', '1500', '0.00', '79.00', '0', '0', '0', '1');
INSERT INTO `luck_product_sku` VALUES ('52', '10', '16143329376657', '1500', '0.00', '79.00', '0', '0', '0', '1');
INSERT INTO `luck_product_sku` VALUES ('53', '10', '16143329377422', '1500', '0.00', '79.00', '0', '0', '0', '1');
INSERT INTO `luck_product_sku` VALUES ('54', '10', '16143329378212', '1500', '0.00', '79.00', '0', '0', '0', '1');
INSERT INTO `luck_product_sku` VALUES ('55', '10', '16143329363384', '1500', '0.00', '79.00', '1', '0', '0', '0');

-- --------------------------------------------------------

-- ----------------------------
-- 表结构 `luck_product_specification`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `luck_product_specification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_model_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL DEFAULT '',
  `sort` mediumint(8) NOT NULL DEFAULT '0',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '1=Enabled 0=Disabled 99=Delete',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COMMENT='产品销售规格';

-- ----------------------------
-- 转存表中的数据 luck_product_specification
-- ----------------------------
INSERT INTO `luck_product_specification` VALUES ('1', '1', '匹数', '0', '1');
INSERT INTO `luck_product_specification` VALUES ('2', '1', '系列', '0', '1');
INSERT INTO `luck_product_specification` VALUES ('3', '2', '尺码', '0', '1');
INSERT INTO `luck_product_specification` VALUES ('4', '2', '颜色', '0', '1');

-- --------------------------------------------------------

-- ----------------------------
-- 表结构 `luck_product_specification_option`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `luck_product_specification_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_specification_id` int(11) NOT NULL DEFAULT '0',
  `value` varchar(100) NOT NULL DEFAULT '',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '1=Enabled 99=Delete',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COMMENT='产品销售规格选项';

-- ----------------------------
-- 转存表中的数据 luck_product_specification_option
-- ----------------------------
INSERT INTO `luck_product_specification_option` VALUES ('1', '1', '1.5匹', '1');
INSERT INTO `luck_product_specification_option` VALUES ('2', '1', '大1匹', '1');
INSERT INTO `luck_product_specification_option` VALUES ('3', '2', '★一级变频★卧室舒适云锦', '1');
INSERT INTO `luck_product_specification_option` VALUES ('4', '2', '★一级变频★线下同款品悦1', '1');
INSERT INTO `luck_product_specification_option` VALUES ('5', '2', '★一级变频★线下同款品悦2', '1');
INSERT INTO `luck_product_specification_option` VALUES ('6', '3', 'XS', '1');
INSERT INTO `luck_product_specification_option` VALUES ('7', '3', 'S', '1');
INSERT INTO `luck_product_specification_option` VALUES ('8', '3', 'M', '1');
INSERT INTO `luck_product_specification_option` VALUES ('9', '3', 'L', '1');
INSERT INTO `luck_product_specification_option` VALUES ('10', '3', 'XL', '1');
INSERT INTO `luck_product_specification_option` VALUES ('11', '3', '2XL', '1');
INSERT INTO `luck_product_specification_option` VALUES ('12', '3', '3XL', '1');
INSERT INTO `luck_product_specification_option` VALUES ('13', '3', '160', '1');
INSERT INTO `luck_product_specification_option` VALUES ('14', '3', '165', '1');
INSERT INTO `luck_product_specification_option` VALUES ('15', '3', '170', '1');
INSERT INTO `luck_product_specification_option` VALUES ('16', '3', '175', '1');
INSERT INTO `luck_product_specification_option` VALUES ('17', '3', '180', '1');
INSERT INTO `luck_product_specification_option` VALUES ('18', '3', '185', '1');
INSERT INTO `luck_product_specification_option` VALUES ('19', '3', '190', '1');
INSERT INTO `luck_product_specification_option` VALUES ('20', '4', '白色', '1');
INSERT INTO `luck_product_specification_option` VALUES ('21', '4', '黑色', '1');
INSERT INTO `luck_product_specification_option` VALUES ('22', '4', '蓝色', '1');
INSERT INTO `luck_product_specification_option` VALUES ('23', '4', '绿色', '1');
INSERT INTO `luck_product_specification_option` VALUES ('24', '4', '黄色', '1');
INSERT INTO `luck_product_specification_option` VALUES ('25', '4', '红色', '1');

-- --------------------------------------------------------

-- ----------------------------
-- 表结构 `luck_product_to_attribute`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `luck_product_to_attribute` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL DEFAULT '0' COMMENT '产品ID',
  `product_attribute_id` int(11) NOT NULL DEFAULT '0' COMMENT '属性ID',
  `product_attribute_value` varchar(150) NOT NULL DEFAULT '' COMMENT '属性值',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='产品关联属性';

-- --------------------------------------------------------

-- ----------------------------
-- 表结构 `luck_product_to_specification`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `luck_product_to_specification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) DEFAULT '0',
  `sku` varchar(200) NOT NULL DEFAULT '',
  `product_specification_id` int(11) NOT NULL DEFAULT '0',
  `product_specification_option_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8mb4 COMMENT='产品关联销售规格';

-- ----------------------------
-- 转存表中的数据 luck_product_to_specification
-- ----------------------------
INSERT INTO `luck_product_to_specification` VALUES ('1', '5', '16143282350692', '1', '2');
INSERT INTO `luck_product_to_specification` VALUES ('2', '5', '16143282350692', '2', '3');
INSERT INTO `luck_product_to_specification` VALUES ('3', '5', '16143282351337', '1', '2');
INSERT INTO `luck_product_to_specification` VALUES ('4', '5', '16143282351337', '2', '4');
INSERT INTO `luck_product_to_specification` VALUES ('5', '5', '16143282351879', '1', '2');
INSERT INTO `luck_product_to_specification` VALUES ('6', '5', '16143282351879', '2', '5');
INSERT INTO `luck_product_to_specification` VALUES ('7', '5', '16143282352407', '1', '1');
INSERT INTO `luck_product_to_specification` VALUES ('8', '5', '16143282352407', '2', '3');
INSERT INTO `luck_product_to_specification` VALUES ('9', '5', '16143282352971', '1', '1');
INSERT INTO `luck_product_to_specification` VALUES ('10', '5', '16143282352971', '2', '4');
INSERT INTO `luck_product_to_specification` VALUES ('11', '5', '16143282353427', '1', '1');
INSERT INTO `luck_product_to_specification` VALUES ('12', '5', '16143282353427', '2', '5');
INSERT INTO `luck_product_to_specification` VALUES ('13', '6', '16143312343266', '3', '6');
INSERT INTO `luck_product_to_specification` VALUES ('14', '6', '16143312357087', '3', '7');
INSERT INTO `luck_product_to_specification` VALUES ('15', '6', '16143312358255', '3', '8');
INSERT INTO `luck_product_to_specification` VALUES ('16', '6', '16143312359558', '3', '9');
INSERT INTO `luck_product_to_specification` VALUES ('17', '6', '16143312360968', '3', '10');
INSERT INTO `luck_product_to_specification` VALUES ('18', '6', '1614331236219', '3', '11');
INSERT INTO `luck_product_to_specification` VALUES ('19', '7', '16143315466215', '3', '7');
INSERT INTO `luck_product_to_specification` VALUES ('20', '7', '16143315471634', '3', '8');
INSERT INTO `luck_product_to_specification` VALUES ('21', '7', '1614331547253', '3', '9');
INSERT INTO `luck_product_to_specification` VALUES ('22', '8', '16143318904182', '3', '7');
INSERT INTO `luck_product_to_specification` VALUES ('23', '8', '16143318910368', '3', '8');
INSERT INTO `luck_product_to_specification` VALUES ('24', '8', '1614331891143', '3', '9');
INSERT INTO `luck_product_to_specification` VALUES ('25', '8', '16143318912482', '3', '10');
INSERT INTO `luck_product_to_specification` VALUES ('26', '9', '16143323329358', '3', '7');
INSERT INTO `luck_product_to_specification` VALUES ('27', '9', '16143323335476', '3', '8');
INSERT INTO `luck_product_to_specification` VALUES ('28', '9', '16143323336234', '3', '9');
INSERT INTO `luck_product_to_specification` VALUES ('29', '9', '16143323336927', '3', '10');
INSERT INTO `luck_product_to_specification` VALUES ('30', '9', '16143323337658', '3', '11');
INSERT INTO `luck_product_to_specification` VALUES ('31', '9', '16143323338338', '3', '12');
INSERT INTO `luck_product_to_specification` VALUES ('32', '10', '16143329363606', '3', '7');
INSERT INTO `luck_product_to_specification` VALUES ('33', '10', '16143329363606', '4', '20');
INSERT INTO `luck_product_to_specification` VALUES ('34', '10', '16143329364618', '3', '7');
INSERT INTO `luck_product_to_specification` VALUES ('35', '10', '16143329364618', '4', '22');
INSERT INTO `luck_product_to_specification` VALUES ('36', '10', '16143329365375', '3', '7');
INSERT INTO `luck_product_to_specification` VALUES ('37', '10', '16143329365375', '4', '23');
INSERT INTO `luck_product_to_specification` VALUES ('38', '10', '16143329366117', '3', '7');
INSERT INTO `luck_product_to_specification` VALUES ('39', '10', '16143329366117', '4', '24');
INSERT INTO `luck_product_to_specification` VALUES ('40', '10', '16143329366847', '3', '7');
INSERT INTO `luck_product_to_specification` VALUES ('41', '10', '16143329366847', '4', '25');
INSERT INTO `luck_product_to_specification` VALUES ('42', '10', '16143329367576', '3', '8');
INSERT INTO `luck_product_to_specification` VALUES ('43', '10', '16143329367576', '4', '20');
INSERT INTO `luck_product_to_specification` VALUES ('44', '10', '16143329368295', '3', '8');
INSERT INTO `luck_product_to_specification` VALUES ('45', '10', '16143329368295', '4', '22');
INSERT INTO `luck_product_to_specification` VALUES ('46', '10', '16143329369045', '3', '8');
INSERT INTO `luck_product_to_specification` VALUES ('47', '10', '16143329369045', '4', '23');
INSERT INTO `luck_product_to_specification` VALUES ('48', '10', '16143329369784', '3', '8');
INSERT INTO `luck_product_to_specification` VALUES ('49', '10', '16143329369784', '4', '24');
INSERT INTO `luck_product_to_specification` VALUES ('50', '10', '16143329370584', '3', '8');
INSERT INTO `luck_product_to_specification` VALUES ('51', '10', '16143329370584', '4', '25');
INSERT INTO `luck_product_to_specification` VALUES ('52', '10', '16143329371324', '3', '9');
INSERT INTO `luck_product_to_specification` VALUES ('53', '10', '16143329371324', '4', '20');
INSERT INTO `luck_product_to_specification` VALUES ('54', '10', '16143329372053', '3', '9');
INSERT INTO `luck_product_to_specification` VALUES ('55', '10', '16143329372053', '4', '22');
INSERT INTO `luck_product_to_specification` VALUES ('56', '10', '16143329372796', '3', '9');
INSERT INTO `luck_product_to_specification` VALUES ('57', '10', '16143329372796', '4', '23');
INSERT INTO `luck_product_to_specification` VALUES ('58', '10', '16143329373584', '3', '9');
INSERT INTO `luck_product_to_specification` VALUES ('59', '10', '16143329373584', '4', '24');
INSERT INTO `luck_product_to_specification` VALUES ('60', '10', '16143329374334', '3', '9');
INSERT INTO `luck_product_to_specification` VALUES ('61', '10', '16143329374334', '4', '25');
INSERT INTO `luck_product_to_specification` VALUES ('62', '10', '1614332937523', '3', '10');
INSERT INTO `luck_product_to_specification` VALUES ('63', '10', '1614332937523', '4', '20');
INSERT INTO `luck_product_to_specification` VALUES ('64', '10', '16143329375973', '3', '10');
INSERT INTO `luck_product_to_specification` VALUES ('65', '10', '16143329375973', '4', '22');
INSERT INTO `luck_product_to_specification` VALUES ('66', '10', '16143329376657', '3', '10');
INSERT INTO `luck_product_to_specification` VALUES ('67', '10', '16143329376657', '4', '23');
INSERT INTO `luck_product_to_specification` VALUES ('68', '10', '16143329377422', '3', '10');
INSERT INTO `luck_product_to_specification` VALUES ('69', '10', '16143329377422', '4', '24');
INSERT INTO `luck_product_to_specification` VALUES ('70', '10', '16143329378212', '3', '10');
INSERT INTO `luck_product_to_specification` VALUES ('71', '10', '16143329378212', '4', '25');

-- --------------------------------------------------------

-- ----------------------------
-- 表结构 `luck_search_hot_word`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `luck_search_hot_word` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` varchar(255) NOT NULL DEFAULT '',
  `sort` mediumint(8) NOT NULL DEFAULT '0',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '0=Disabled 1=Enabled',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- 转存表中的数据 luck_search_hot_word
-- ----------------------------
INSERT INTO `luck_search_hot_word` VALUES ('1', '小米', '0', '2021-02-27 20:46:32', '1');
INSERT INTO `luck_search_hot_word` VALUES ('2', '格力', '0', '2021-02-27 20:46:49', '1');
INSERT INTO `luck_search_hot_word` VALUES ('3', '海信', '0', '2021-02-27 20:47:01', '1');
INSERT INTO `luck_search_hot_word` VALUES ('4', '电视', '0', '2021-02-27 20:47:19', '1');
INSERT INTO `luck_search_hot_word` VALUES ('5', '空调', '0', '2021-02-27 20:47:42', '1');
INSERT INTO `luck_search_hot_word` VALUES ('6', '连衣裙', '0', '2021-02-27 20:48:56', '1');

-- --------------------------------------------------------

-- ----------------------------
-- 表结构 `luck_section`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `luck_section` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `client` varchar(25) NOT NULL DEFAULT 'wxapp' COMMENT '客户端 wxapp|pc',
  `type` varchar(20) NOT NULL DEFAULT '' COMMENT '类型 system=系统使用',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- 转存表中的数据 luck_section
-- ----------------------------
INSERT INTO `luck_section` VALUES ('1', '精品推荐', '电脑端精品推荐板块使用', 'pc', 'system', '2021-02-26 17:58:04');
INSERT INTO `luck_section` VALUES ('2', '精品推荐', '微信小程序端精品推荐板块使用', 'wxapp', 'system', '2021-02-28 14:40:46');

-- --------------------------------------------------------

-- ----------------------------
-- 表结构 `luck_section_value`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `luck_section_value` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section_id` int(11) NOT NULL DEFAULT '0',
  `value_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- 转存表中的数据 luck_section_value
-- ----------------------------
INSERT INTO `luck_section_value` VALUES ('1', '1', '5');
INSERT INTO `luck_section_value` VALUES ('2', '1', '4');
INSERT INTO `luck_section_value` VALUES ('3', '1', '3');
INSERT INTO `luck_section_value` VALUES ('4', '1', '2');
INSERT INTO `luck_section_value` VALUES ('5', '1', '1');
INSERT INTO `luck_section_value` VALUES ('7', '1', '9');
INSERT INTO `luck_section_value` VALUES ('8', '1', '8');
INSERT INTO `luck_section_value` VALUES ('9', '1', '7');
INSERT INTO `luck_section_value` VALUES ('10', '1', '6');
INSERT INTO `luck_section_value` VALUES ('11', '1', '10');
INSERT INTO `luck_section_value` VALUES ('12', '2', '5');
INSERT INTO `luck_section_value` VALUES ('13', '2', '4');
INSERT INTO `luck_section_value` VALUES ('14', '2', '3');
INSERT INTO `luck_section_value` VALUES ('15', '2', '2');
INSERT INTO `luck_section_value` VALUES ('16', '2', '1');
INSERT INTO `luck_section_value` VALUES ('17', '2', '10');
INSERT INTO `luck_section_value` VALUES ('18', '2', '9');
INSERT INTO `luck_section_value` VALUES ('19', '2', '8');
INSERT INTO `luck_section_value` VALUES ('20', '2', '7');
INSERT INTO `luck_section_value` VALUES ('21', '2', '6');

-- --------------------------------------------------------

-- ----------------------------
-- 表结构 `luck_shipping_freight`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `luck_shipping_freight` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `type` tinyint(3) NOT NULL DEFAULT '1' COMMENT '计价方式 1=件数 2=重量 3=体积',
  `description` varchar(255) NOT NULL DEFAULT '',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '0=Disabled 1=Enabled 99=Delete',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='运费模板';

-- ----------------------------
-- 转存表中的数据 luck_shipping_freight
-- ----------------------------
INSERT INTO `luck_shipping_freight` VALUES ('1', '全国统一运费', '1', '全国统一运费模板演示', '2021-02-26 15:52:22', '2021-02-26 15:52:22', '1');

-- --------------------------------------------------------

-- ----------------------------
-- 表结构 `luck_shipping_freight_value`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `luck_shipping_freight_value` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `freight_id` int(11) NOT NULL DEFAULT '0',
  `first_key` varchar(100) NOT NULL DEFAULT '' COMMENT '首件/首重/首体积',
  `first_value` decimal(10,2) NOT NULL DEFAULT '0.00',
  `second_key` varchar(100) NOT NULL DEFAULT '' COMMENT '续件/续重/续体积',
  `second_value` decimal(10,2) NOT NULL DEFAULT '0.00',
  `ship_area` varchar(255) NOT NULL DEFAULT '0' COMMENT '配送区域 0=全国 城市ID以,切割',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='运费模板值';

-- ----------------------------
-- 转存表中的数据 luck_shipping_freight_value
-- ----------------------------
INSERT INTO `luck_shipping_freight_value` VALUES ('1', '1', '10', '9.00', '5', '3.00', '0');

-- --------------------------------------------------------

-- ----------------------------
-- 表结构 `luck_shipping_mark`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `luck_shipping_mark` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `sort` mediumint(8) NOT NULL DEFAULT '0',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '0=Disabled 1=Enabled',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='配送方式';

-- ----------------------------
-- 转存表中的数据 luck_shipping_mark
-- ----------------------------
INSERT INTO `luck_shipping_mark` VALUES ('1', '顺丰快递', '顺丰速运有限公司', '0', '1');
INSERT INTO `luck_shipping_mark` VALUES ('2', '圆通速递', '圆通速递有限公司', '0', '1');

-- --------------------------------------------------------

-- ----------------------------
-- 表结构 `luck_sms_code`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `luck_sms_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phone` varchar(20) NOT NULL DEFAULT '',
  `code` varchar(10) NOT NULL DEFAULT '',
  `is_use` tinyint(3) NOT NULL DEFAULT '0',
  `type` varchar(50) NOT NULL DEFAULT '',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `index_phone` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='短信验证码';

-- --------------------------------------------------------

-- ----------------------------
-- 表结构 `luck_sms_log`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `luck_sms_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phone` varchar(20) NOT NULL DEFAULT '',
  `content` varchar(255) NOT NULL DEFAULT '',
  `type` varchar(50) NOT NULL DEFAULT '',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='短信发送记录';

-- --------------------------------------------------------

-- ----------------------------
-- 表结构 `luck_sudoku`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `luck_sudoku` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL DEFAULT '',
  `image` varchar(255) NOT NULL DEFAULT '',
  `page_url` varchar(255) NOT NULL DEFAULT '' COMMENT '页面链接',
  `page_ident` tinyint(3) NOT NULL DEFAULT '1' COMMENT '页面标识 1=内部页面 2=外部链接',
  `sort` mediumint(8) NOT NULL DEFAULT '0',
  `type` varchar(25) NOT NULL DEFAULT 'wxapp' COMMENT 'wxapp|wap',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '1=Enabled 99=Delete',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COMMENT='九宫格';

-- ----------------------------
-- 转存表中的数据 luck_sudoku
-- ----------------------------
INSERT INTO `luck_sudoku` VALUES ('3', '电视', '/images/wxapp/sudoku/icon_01[luck1614439268luck].png', '/pages/product_list/index?category_id=11', '1', '0', 'wxapp', '1');
INSERT INTO `luck_sudoku` VALUES ('4', '空调', '/images/wxapp/sudoku/icon_02[luck1614439268luck].png', '/pages/product_list/index?category_id=12', '1', '0', 'wxapp', '1');
INSERT INTO `luck_sudoku` VALUES ('5', '手机', '/images/wxapp/sudoku/icon_03[luck1614439268luck].png', '/pages/product_list/index?category_id=165', '1', '0', 'wxapp', '1');
INSERT INTO `luck_sudoku` VALUES ('6', '男装', '/images/wxapp/sudoku/icon_04[luck1614439268luck].png', '/pages/product_list/index?category_id=3', '1', '0', 'wxapp', '1');
INSERT INTO `luck_sudoku` VALUES ('7', '女装', '/images/wxapp/sudoku/icon_05[luck1614439268luck].png', '/pages/product_list/index?category_id=2', '1', '0', 'wxapp', '1');
INSERT INTO `luck_sudoku` VALUES ('8', '积分商城', '/images/wxapp/sudoku/icon_06[luck1614439268luck].png', '/pages/integral/products', '1', '0', 'wxapp', '1');
INSERT INTO `luck_sudoku` VALUES ('9', '签到', '/images/wxapp/sudoku/icon_07[luck1614439268luck].png', '/pages/qiandao/index', '1', '0', 'wxapp', '1');
INSERT INTO `luck_sudoku` VALUES ('10', '箱包', '/images/wxapp/sudoku/icon_08[luck1614439268luck].png', '/pages/product_list/index?category_id=4', '1', '0', 'wxapp', '1');
INSERT INTO `luck_sudoku` VALUES ('11', '商品', '/images/wxapp/sudoku/icon_03[luck1614439268luck].png', '/pages/product_list/index', '1', '0', 'wxapp', '1');
INSERT INTO `luck_sudoku` VALUES ('12', '分类', '/images/wxapp/sudoku/icon_08[luck1614439268luck].png', '/pages/product_list/index', '1', '0', 'wxapp', '1');

-- --------------------------------------------------------

-- ----------------------------
-- 表结构 `luck_user`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `luck_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nickname` varchar(100) NOT NULL DEFAULT '',
  `email` varchar(96) NOT NULL DEFAULT '',
  `phone` varchar(11) NOT NULL DEFAULT '',
  `avatar` varchar(255) NOT NULL DEFAULT '',
  `sex` tinyint(3) NOT NULL DEFAULT '0' COMMENT '0=未知 1=男 2=女',
  `password` varchar(100) NOT NULL DEFAULT '',
  `wallet` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT '账户余额 钱包',
  `integral` int(20) NOT NULL DEFAULT '0' COMMENT '积分',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '0=Disabled 1=Enabled',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户表';

-- --------------------------------------------------------

-- ----------------------------
-- 表结构 `luck_user_address`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `luck_user_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(20) NOT NULL DEFAULT '',
  `province_id` int(11) NOT NULL DEFAULT '0',
  `city_id` int(11) NOT NULL DEFAULT '0',
  `district_id` int(11) NOT NULL DEFAULT '0',
  `detailed_address` text NOT NULL COMMENT '详细地址',
  `phone` varchar(20) NOT NULL DEFAULT '0' COMMENT '手机号',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(3) NOT NULL DEFAULT '2' COMMENT '1=defaule address 2=general address 99=delete',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

-- ----------------------------
-- 表结构 `luck_user_collect_product`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `luck_user_collect_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `sku` varchar(200) NOT NULL DEFAULT '',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

-- ----------------------------
-- 表结构 `luck_user_integral_log`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `luck_user_integral_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `integral` int(11) NOT NULL DEFAULT '0',
  `ident` tinyint(3) NOT NULL DEFAULT '1' COMMENT '1=加 2=减',
  `description` varchar(255) NOT NULL DEFAULT '',
  `source` varchar(20) NOT NULL DEFAULT 'qiandao',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='积分记录';

-- --------------------------------------------------------

-- ----------------------------
-- 表结构 `luck_user_login_log`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `luck_user_login_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `token` varchar(100) NOT NULL DEFAULT '',
  `ip` varchar(25) NOT NULL DEFAULT '',
  `browser` varchar(20) NOT NULL DEFAULT '',
  `browser_version` varchar(20) NOT NULL DEFAULT '',
  `login_device` varchar(20) NOT NULL DEFAULT '' COMMENT '登录设备 wxapp|web',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(3) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

-- ----------------------------
-- 表结构 `luck_user_qiandao_log`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `luck_user_qiandao_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `date` varchar(20) NOT NULL DEFAULT '',
  `client` varchar(20) NOT NULL DEFAULT 'wxapp' COMMENT '客户端',
  `continuous_count` mediumint(8) NOT NULL DEFAULT '1',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户签到记录';

-- --------------------------------------------------------

-- ----------------------------
-- 表结构 `luck_user_thirdlogin`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `luck_user_thirdlogin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `type` varchar(20) NOT NULL DEFAULT '' COMMENT 'qq|weixin|weibo',
  `openid` varchar(255) NOT NULL DEFAULT '' COMMENT '第三方登录标识',
  `unionid` varchar(255) NOT NULL DEFAULT '' COMMENT '第三方登录跨平台标识',
  `nickname` varchar(255) NOT NULL DEFAULT '' COMMENT '第三方账户昵称',
  `data` text NOT NULL COMMENT '第三方登录返回数据',
  `device` varchar(20) NOT NULL DEFAULT '' COMMENT '区分客户端 web|wxapp',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '1=正常绑定',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户第三方帐户数据';

-- --------------------------------------------------------
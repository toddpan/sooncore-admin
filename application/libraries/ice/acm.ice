//[["java:package:com.gnet.pg"]]
module acmmodule
{
	sequence<string> StrList;
	sequence<int> IntList;
	sequence<bool> BoolList;
	dictionary<string, string> StrMap;
	dictionary<string, int> StrIntMap;
	dictionary<string, bool> StrBoolMap;
	sequence<StrMap> StrMapList;
	
	//外呼的电话号码对象，可以使用4个域的形式，也可以使用完整号码的格式
	struct DialoutPhoneNum
	{
		string countryCode;		//电话的国家码
		string areaCode;		//电话的区号
		string number;			//电话的号码
		string extNumber;		//电话的分机号
		
		string phone;			//字符串形式的电话号码
	};
		
	struct DialoutPartyEntity
	{
		string name;				//外呼会议方的名字
		DialoutPhoneNum phoneNum;	//外呼会议方的电话号码对象
		int role;					//外呼会议方的角色
		string userDefined;			//外呼会议方的自定义属性
		string pin;					//外呼会议方的Pin码
		string userDefined2;		//外呼会议方的自定义属性2
		string userDefined3;		//外呼会议方的自定义属性3
	};
	
	sequence<DialoutPartyEntity> DialoutPartyEntityList;
	
	//系统返回的电话号码对象
	struct PhoneNum
	{
		//电话号码
		string phoneNumber;				//字符串形式的电话号码，完整格式，国家码用括号括起，分机用-分割
		string phoneNationalNumber;		//在国家内部使用的号码，不带国家码，分机用-分开。国内手机不带0，固话带0；国外的不处理
		string phoneCountryCode;		//电话的国家码
		string phoneAreaCode;			//电话的区号，号码是国内号码时才提供，不带0
		string phoneAreaName;			//电话所在地名称（国内的是城市名称，国外的是国家名称）
		
		//接入号
		string accessNumber;			//接入号，ACM格式完整的号码，国家码用括号括起
		string accessNumberCountryCode;	//接入号所在国家编码
		string accessNumberAreaCode;	//接入号区号，接入号是国内号码时才提供，不带0
		string accessNumberAreaName;	//接入号地区名称（国内的是城市名称，国外的是国家名称）
	};	
	
	//所有异步消息的父类
    class Event
	{
		long sid;				//消息的顺序号
		int id;					//消息类型
		string billingCode;		//电话会议标识
		string bridgeName; 		//会议桥的名称
		string bridgeType; 		//会议桥类型
		int	subConfNumber;		//子会议号：0是主会		
	};
	
	sequence<Event> EventList;
	
	//会议开始消息
	class EventConfStart extends Event
	{
		long startTime; 		//会议开始时间
		string hostCode; 		//主持人密码
		string guestCode;		//参与人密码
	};
	
	//会议结束消息
	class EventConfEnd extends Event
	{
		long endTime;	 		//会议结束时间
	};
	
	//会议Living消息
	class EventConfLiving extends Event
	{
		long livingTime; 		//会议结束时间
	};
		
	//会议状态，包括：锁定，录音，全体静音，Q&A，Voting，WaitingLine，Talker
	class EventConfState extends Event 
	{	
		string hostCode; 		//主持人密码
		string guestCode;		//参与人密码
		long startTime; 		//会议开始时间
		int maxParties; 		//最大方数
		int dialoutAllowed;		//是否允许外呼
		bool pnr;				//入会是否需要录制姓名
		bool chairDisc;			//主持人断线时会议是否结束
		IntList entryExitTone;	//进出会议方提示音
		int lockState;			//锁定
		bool recordEnabled;		//是否能录音
		int recordState;		//录音
		bool quickStart;		//主持人未入会会议能不能开始
		int muteState;			//全体静音
		int qaState;			//QA
		int votingState;		//投票
		int guestAudioMode;		//参与人进入会议的状态
		bool guestMuteOverride;	//参与人*6屏蔽*4
	};
	
	//会议方状态消息
	class EventPartyState extends Event
	{
		string partyID; 			//PartyID
		string name; 				//会议方姓名
		PhoneNum partyPhoneNum;		//电话号码对象
		int role;					//角色：0 参与人；1 主持人
		string pin; 				//Pin码
		string userDefined;			//UserDefined
		int connectState; 			//连接状态
		int disconnectReason; 		//断开原因
		int muteReason;		 		//被静音的原因
		bool isRequestOpHelp; 		//是否请求了Operator Help
		long connectTime; 			//连接时间
		long inConfTime; 			//进入会议时间
		long operatorTime; 			//和Operator通话的开始时间
		long disconnectTime;		//断开时间
		string dataConfID; 			//9#码
		bool dialedIn;				//是否主动接入
		string userDefined2;		//UserDefined2
		string userDefined3;		//UserDefined3
	};
	
	//会议方删除
	class EventPartyDel extends Event
	{
		string partyID;
	};
	
	//会议Talker状态
	class EventConfTalkerState extends Event 
	{
		int state;				//状态
	};
	
	//插播线路状态
	class EventConfWaitingLineState extends Event 
	{
		int state;				//状态
	};
	
	//QA结果
	class EventQAResult extends Event
	{	
		StrList moderatorList;	//ModeratorList列表
		string floorPartyID; 	//当前Question的会议方
		StrList queueList; 		//提问者列表
	};
	
	//投票配置
	class EventVotingConfig extends Event
	{		
		string question;		//投票问题
		string choiceText1;		//第1个选项的文本
		string choiceText2;		//第2个选项的文本
		string choiceText3;		//第3个选项的文本
		string choiceText4;		//第4个选项的文本
		string choiceText5;		//第5个选项的文本
		string choiceText6;		//第6个选项的文本
		string choiceText7;		//第7个选项的文本
		string choiceText8;		//第8个选项的文本
		string choiceText9;		//第9个选项的文本
	};
	
	//Voting状态消息
	class EventVotingResult extends Event
	{
		bool tallyCompleted; 	//是否是全部的投票结果
		int noVotes; 			//没有投票的人数
		int votesFor1;			//投选项1的人数
		int votesFor2;			//投选项2的人数
		int votesFor3;			//投选项3的人数
		int votesFor4;			//投选项4的人数
		int votesFor5;			//投选项5的人数
		int votesFor6;			//投选项6的人数
		int votesFor7;			//投选项7的人数
		int votesFor8;			//投选项8的人数
		int votesFor9;			//投选项9的人数
	};
	
	//会议Taker状态消息
	class EventPartyTalker extends Event
	{
		StrBoolMap stateMap;
	};
	
	//疑似噪音消息
	class EventNoiseParty extends Event
	{
		StrList noisePartyList;
	};
	
	//自定义录音状态消息
	class EventRecordStateAcm extends Event
	{
		int state;		//自定义录音状态
	};
	
	//注册时会议消息发送结束标记，忽略父类中除 sid和id之外的字段
	class EventSendConfsFinished extends Event
	{	
	};
	
	//订阅时会议方发送结束标记
	class EventSendPartiesFinished extends Event
	{
	};
	
	interface AcmServer
    {
    	//注册
    	["ami","amd"] int register(StrMap clientInfo, out string ssid);
    	
    	//取消 0成功，-999 ssid不存在
    	["ami"] int unregister(string ssid);
    	
    	//收取异步消息
    	["ami"] int getEvent(string ssid, out Event evt);
    	
    	//收取全部异步消息
    	["ami"] int getAllEvent(string ssid, out EventList evtList);
    };
    
	interface ConfControl
    {    	
		//判断电话会议是否开启
    	["ami"] int isConfActive(string ssid, string billingCode, string bridgeName);
		
		//激活会议	
    	["ami"] int confActivate(string ssid, string billingCode, string bridgeName);
    	
    	//开启子会议
		["ami"] int subConfActivate(string ssid, string billingCode, string bridgeName, IntList subConfNumberList);
    	  	
		//订阅会议。
		//flag: 0为取消订阅，1订阅
		//订阅时返回按会议时间排序的activeConfsList，格式是BridgeName_会议号字符串，主会为0。比如summit1_0（只有主会），r1_012（主会和2个子会）
		["ami"] int subscribeConference(string ssid, string billingCode, int flag, out StrList activeConfsList);
		
		//请求关闭会议
		// subConfNumber: 0  主会议；1-9 子会议
		["ami"] int confClose(string ssid, string billingCode, int subConfNumber, string bridgeName);

		//删除电话与会者
		["ami"] int kickOutParty(string ssid, string billingCode, StrList partyIDs);
		
		//挂断电话与会者
		["ami"] int hangupParty(string ssid, string billingCode, StrList partyIDs);
	
		//设置外呼时的提示音和是否需要*1入会
		//msgToPlay：0 无提示音；1 标准提示音； 2-3001 个性化提示音
		//joinConfirmationRequired：false 不需*1；true 需要*1
		["ami"] int setBlastOption(string ssid, string billingCode, string bridgeName, int msgToPlay, bool joinConfirmationRequired);
		
		//单个外呼新的会议方
		["ami"] int dialoutParty(string ssid, string billingCode, int subConfNumber, string bridgeName, DialoutPartyEntity party, out string partyID);
		
		//批量外呼新的会议方
		["ami","amd"] int dialoutParties(string ssid, string billingCode, int subConfNumber, string bridgeName, DialoutPartyEntityList partyList);
		
		//批量外呼已存在的会议方：Summit only
		["ami"] int blastParties(string ssid, string billingCode, StrList partyIDList);
        
		//电话用户自我静音
		//modeState：1自我静音； 0 解除自我静音; 
		//playMessage：false不播放提示音； true播放提示音
		["ami"] int mutePartySelf(string ssid, string billingCode, string partyID, int modeState, bool playMessage); 
		
		//将电话用户静音
		//modeState：1 静音； 0解除静音; 
		//playMessage：0不播放提示音； 1 播放提示音
		["ami"] int muteParty(string ssid, string billingCode, StrList partyIDs, int modeState, bool playMessage);
    
		//会场静音/取消静音
		["ami"] int confMute(string ssid, string billingCode, string bridgeName, int muteState);
		
		//会议锁定/取消锁定
		["ami"] int confLock(string ssid, string billingCode, string bridgeName, int lockState);
		
		//会议录音
		["ami","amd"] int confRecord(string ssid, string billingCode, string bridgeName, int recordState);
		
		//修改会议进出提示音
		["ami"] int setConfEntryExitConfig(string ssid, string billingCode, string bridgeName, int hostEntry, int hostExit, int guestEntry, int guestExit);
		
		//重置会议时长
		["ami"] int setConfDuration(string ssid, string billingCode, string bridgeName, int duration);		
		
		//开启/停止会场Q&A
		//state：1 开启； 0停止 
		["ami"] int confQa(string ssid, string billingCode, string bridgeName, int state);
		
		//Q&A Promote
		["ami"] int confQaPromote(string ssid, string billingCode, string partyID);
		
		//Q&A remove
		["ami"] int confQaRemove(string ssid, string billingCode, string partyID);
		
		//Q&A MA
		["ami"] int confQaModeratorAdd(string ssid, string billingCode, StrList partyIDs);
		
		//Q&A MR
		["ami"] int confQaModeratorRemove(string ssid, string billingCode, StrList partyIDs);
		
		//会场Q&A重排序
		["ami"] int confQaReorder(string ssid, string billingCode, string partyID, int position);
		
		//开启会场Voting
		["ami"] int confVotingStart(string ssid, string billingCode, string bridgeName, string question, StrList choiceText);
		
		//停止会场投票
		["ami"] int confVotingStop(string ssid, string billingCode, string bridgeName);
				
		//会场Waiting Line
		["ami"] int confWaitingLine(string ssid, string billingCode, string bridgeName, int state, int index);		
		
		//会议方请求operater协助
		["ami"] int partyOperatorSignal(string ssid, string billingCode, string partyID, bool signal);

		//修改会议方的Name
		["ami"] int changePartyName(string ssid, string billingCode, string partyID, string newName);
		
		//修改用户的角色
		["ami"] int changePartyRole(string ssid, string billingCode, string partyID, int role);
		
		//修改电话号码
		["ami"] int changePartyPhone(string ssid, string billingCode, string partyID, DialoutPhoneNum newPhone);
		
		//修改会议方的UserDefined
		["ami"] int changePartyUserDefined(string ssid, string billingCode, string partyID, string value);
		
		//修改会议方的UserDefined2
		["ami"] int changePartyUserDefined2(string ssid, string billingCode, string partyID, string value);
		
		//修改会议方的UserDefinded3
		["ami"] int changePartyUserDefined3(string ssid, string billingCode, string partyID, string value);
		
		//开启/关闭Taler功能
		//request：1 打开； 0 关闭
		["ami"] int confTalker(string ssid, string billingCode, string bridgeName, int state);
		
		//转移会议方
		["ami"] int transferParty(string ssid, string billingCode, StrList partyIDList, int toSubConfNumber, bool playMessage);
    };
    
    interface CommonService
    {    	
		//获得标准格式的接入号和主叫号码
		["ami"] StrList getStandardAccessNumberAni(string ssid, string bridgeName, string dnis, string ani);
		
		//获取国内手机的归属地，地区名称和运营商
		["ami"] StrList getCellPhoneArea(string ssid, string cellPhoneNumber);
    };
    
    interface ConfReserve
    {
		//开通MeetMe或帐号
		["ami"] int reserveConf(string ssid, StrMap msgMap, out string responseDetail);
		
		//预约会议
		["ami"] int scheduleConf(string ssid, string accountBillingCode, string bridgeName, StrMap msgMap, out string responseDetail);
		
		//删除会议指定平台的会议或帐号，Summit可以恢复，Radisys不可以恢复
		["ami"] int deleteConf(string ssid, string billingCode, string bridgeName, out string responseDetail);
		
		//暂停会议指定平台的会议或帐号，可以恢复
		["ami"] int suspendConf(string ssid, string billingCode, string bridgeName, out string responseDetail);
		
		//修改会议或帐号属性，需要指定平台
		["ami"] int updateConf(string ssid, string billingCode, string bridgeName, StrMap msgMap, out string responseDetail);
				
		//根据BillingCode查询会议信息
		["ami"] int getConfInfo(string ssid, string billingCode, out StrMap confInfoMap);
		
		//根据密码和密码类型查询BillingCode
		["ami"] int getConfInfoByPasscode(string ssid, string passcode, int role, out StrMap confInfoMap);
    };
    
    interface ConfPin
    {
    	//添加Pin码
		["ami"] int addPin(string ssid, string billingCode, StrMapList pinInfoList, out string responseDetail);
	
		//批量删除指定的Pin
		["ami"] int deletePin(string ssid, string billingCode, string pin);
			
		//删除该会议所有的Pin	
		["ami"] int deletePins(string ssid, string billingCode);
    };
    
    interface DnisAccess
    {
    	// 设置客户接入号黑名单
		["ami"] int setCustomerDnisAccess(string ssid, string customerCode, StrList payType, bool forceUpdateAllConf);
		
		// 设置会议接入号黑名单
		["ami"] int setConfDnisAccess(string ssid, string billingCode, StrList payType);
		
		// 查询会议接入号黑名单
		["ami"] int getConfDnisAccess(string ssid, string billingCode, out StrList payType);
    };
    
	interface CustomerBridge
    {
    	//设置客户和平台的关系
    	["ami"] int setCustomerBridge(string ssid, string customerCode, string bridgeName);
    	
		//删除客户和平台的关系
		["ami"] int deleteCustomerBridge(string ssid, string customerCode);    	
    };
    
    interface PartyLimit
    {
    	//设置客户级策略
		["ami"] int setCustomerPartyLimit(string ssid, string customerCode, int partyLimit, int maxMinutes, bool isDestroyConf);
		
		//删除客户级策略
		["ami"] int removeCustomerPartyLimit(string ssid, string customerCode, bool updateAllConfs);
		
		//设置会议级策略
		["ami"] int setConfPartyLimit(string ssid, string billingCode, int partyLimit, int maxMinutes, bool isDestroyConf);

		//删除会议级策略
		["ami"] int removeConfPartyLimit(string ssid, string billingCode, bool useCustomerPartyLimit);
    
    };
};

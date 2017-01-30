;zetro_users control

[userlist]
1|User ID,input,text n,userid,w35,,15%
2|User Name,input,text n,username,w90,,25%
3|User Level,select,text n,idlevel,S50,,15%,RD,user_level-idlevel-nmlevel-where idlevel!='1' order by nmlevel,AB
4|Password,input,password n,password,w70,,
5|Default user location,select,text,lokasi,s50,,,RD,user_lokasi-ID-lokasi-

[username]
1|User ID,select,text n,id_user,S70,,,RD,users-userid-userid+username-where idlevel!='1' order by username

[usergroup]
1|Modul Name,select,text n,nm_modul,S50,,
2|Group Name,select,text n,nm_group,S50,,80%,RD,user_level-idlevel-nmlevel-where idlevel!='1' order by nmlevel

[useroto]
1|Modul name,,,nm_modul,,,40%
2|Input,,,c,,,5%
3|Edit,,,e,,,5%
4|View,,,v,,,5%

[changepwd]
1|,input,hidden n ,userid,w35,,
2|Old Password,input,password n,old_password,w50,,
3|New Password,input,password n,new_password,w50,,
4|Re New Password,input,password n,re_password,w50,,

[addlevel]
1|ID Level,input,text n,idlevel,w15,,5%
2|Level Name,input,text n,nmlevel,w90,,20%



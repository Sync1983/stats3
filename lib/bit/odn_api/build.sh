#!/bin/sh
DIRNAME=`dirname $0`
DIRNAME=`realpath $DIRNAME`
#mxmlc \
fcshctl mxmlc \
-compiler.show-binding-warnings=true \
-compiler.show-actionscript-warnings=true \
-compiler.source-path=$DIRNAME/src/,$DIRNAME/lib/ \
-compiler.include-libraries=swc/JSONLite.swc \
-static-link-runtime-shared-libraries=true \
 -output $DIRNAME/odn_api.swf \
$DIRNAME/odn_api.mxml

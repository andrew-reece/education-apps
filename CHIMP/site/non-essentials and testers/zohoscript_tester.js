/* $Id: jsonform.js,v 1.12 2007/11/05 12:06:00 mukesh Exp $ */

var Zoho = new Object();

Zoho.labelArr = new Array();
Zoho.inputArr = new Array();
Zoho.formId;
Zoho.serverName;
Zoho.formCaptchaInput;
Zoho.formCaptchaLabel;

Zoho.oldCaptTxt = "oldCaptTxt";
Zoho.captchaName = "captcha";
Zoho.captchaImgName = "hipImg";

Zoho.captcha = '';

Zoho.imgSrc = "zoho_img_src";
Zoho.imgAlt = "zoho_img_alt";
Zoho.imgLink = "zoho_img_link";
Zoho.imgTitle = "zoho_img_title";

Zoho.imgSrcLabel = "Source";
Zoho.imgAltLabel = "Alt Text";
Zoho.imgLinkLabel = "Link";
Zoho.imgTitleLabel = "Title";

Zoho.urlUrl = "zoho_url_url";
Zoho.urlLink = "zoho_url_link";
Zoho.urlTitle = "zoho_url_title";

Zoho.urlUrlLabel = "Url";
Zoho.urlLinkLabel = "Link Name";
Zoho.urlTitleLabel = "Title";

Zoho.TEXT=1;
Zoho.TEXT_AREA=3;
Zoho.EMAIL_ADDRESS=4;
Zoho.NUMBER=5;
Zoho.CURRENCY=6;
Zoho.PERCENTAGE=7;
Zoho.CHECK_BOX=9;
Zoho.DATE=10;
Zoho.TIME=11;
Zoho.PLAIN_TEXT=14;
Zoho.SCRIPT=15;
Zoho.FLOAT=16;
Zoho.FROMTO=17;
Zoho.FILE_UPLOAD=18;
Zoho.DECIMAL=19;
Zoho.IMAGE=20;
Zoho.URL=21;
Zoho.DATE_TIME=22;
Zoho.PICK_LIST=100;
Zoho.RADIO=101;
Zoho.LIST=102;
Zoho.CHECK_BOXES=103;
Zoho.IMPORT_DATA=104;

Zoho.submit = function(frm) {
	frm.appendChild(getInp("sharedBy", frm.getAttribute("user")));
	frm.appendChild(getInp("formid", Zoho.formId));
	//frm.appendChild(getInp("nexturl", frm.getAttribute("nexturl")));
	frm.appendChild(getInp("scriptembed", "true"));
	frm.action = "print_form_data.php";
	frm.method = "post"; 
	frm.submit();
}


function getInp( key, val) {
	var elem = document.createElement("input");
	elem.type="hidden";
	elem.setAttribute("name", key);
	elem.setAttribute("value", val);
	return elem;
}


Zoho.writeLabel = function(fieldname,subvar) {
	if(subvar) {
		fieldname = fieldname + subvar;
	}
	var towrite;
	if(fieldname == Zoho.captcha) {
		towrite = Zoho.formCaptchaLabel;
	} else {
		towrite = Zoho.labelArr[fieldname];
	}
	if(towrite) {
		document.write(towrite);
	}
}

Zoho.writeInput = function(fieldname, subvar) {
	if(subvar) {
		fieldname = fieldname + subvar;
	}
	var towrite;
	if(fieldname == Zoho.captcha) {
		towrite = Zoho.formCaptchaInput;
	} else {
		towrite = Zoho.inputArr[fieldname];
	}
	if(towrite) {
		document.write(towrite);
	}
}

Zoho.writeSubmit = function(displayValue) {
	var dispSubmit = "Submit";
	if(!displayValue) {
		displayValue = dispSubmit;
	}
	document.write("<input type = 'submit' value='"+ displayValue +"'/>");
}
Zoho.writeReset = function(displayValue) {
	var dispSubmit = "Reset";
	if(!displayValue) {
		displayValue = dispSubmit;
	}
	document.write("<input type = 'reset' value='"+ displayValue +"'/>");
}
Zoho.parseForm = function(user, fid, srvName, jsonData) {
	Zoho.formId = fid;
	Zoho.serverName = srvName;
	Zoho.captcha = "captcha_" + fid;
	var valueArray = new Array();
	if(typeof(jsonData.Fields) == "object") {
		for(var eachParameter in jsonData.Fields) {
			var zohoElement = jsonData.Fields[eachParameter];

			if(typeof(zohoElement) == "object")	{
				var inputVal = '';
				var labelDisp = zohoElement.DisplayName;
				if(zohoElement.Reqd)	{
					labelDisp = "* " + labelDisp;
				}
				Zoho.labelArr[zohoElement.FieldName] = labelDisp;
				switch(zohoElement.Type)	{
				case Zoho.TEXT: 
				//text
					valueArray = new Array();
					valueArray['name'] = zohoElement.FieldName;
					valueArray['type'] = "text";
					valueArray['maxlength'] = zohoElement.MaxChar;
					//valueArray['size'] = zohoElement.FieldWidth;
					valueArray['value'] = zohoElement.Initial;
					inputVal = Zoho.getHtmlForElement("input",valueArray);
					
				break;

				case Zoho.EMAIL_ADDRESS: case Zoho.NUMBER: case Zoho.CURRENCY: case Zoho.PERCENTAGE: case Zoho.DECIMAL: 
				//other text	
					valueArray = new Array();
					valueArray['name'] = zohoElement.FieldName;
					valueArray['type'] = "text";
					valueArray['maxLength'] = zohoElement.MaxChar;
					valueArray['value'] = zohoElement.Initial;
					inputVal = Zoho.getHtmlForElement("input",valueArray);
				break;
				
				case Zoho.DATE:
				//date alone
					valueArray = new Array();
					valueArray['name'] = zohoElement.FieldName;
					valueArray['type'] = "text";
					valueArray['value'] = zohoElement.Initial;
					inputVal = Zoho.getHtmlForElement("input",valueArray) + " [dd-mmm-yyyy]";
				break;
				
				case Zoho.DATE_TIME:
				//date and time field
					valueArray = new Array();
					valueArray['name'] = zohoElement.FieldName;
					valueArray['type'] = "text";
					valueArray['value'] = zohoElement.Initial;
					//valueArray['delugetype'] = "TIMESTAMP";
					inputVal = Zoho.getHtmlForElement("input",valueArray) + "  [dd-mmm-yyyy hh:mm:ss]";
				break;
				
				case Zoho.TEXT_AREA:
				//textarea
					valueArray = new Array();
					valueArray['name'] = zohoElement.FieldName;
					valueArray['cols'] = 65;
					valueArray['rows'] = 4;
					inputVal = Zoho.getHtmlForElement("textarea",valueArray) + "</textarea>";
				break;

				case Zoho.IMAGE:
					var altTxtReq = zohoElement.altTxtReq;
					var imgLinkReq = zohoElement.imgLinkReq;
					var imgTitleReq = zohoElement.imgTitleReq;

					inputVal = "<table><tbody><tr><td>" + Zoho.imgSrcLabel + "</td><td>";

					var buildSubImageName = zohoElement.FieldName + Zoho.imgSrc;
					var sourceAlone = Zoho.getIndividualField("zcsource-",zohoElement.FieldName);
					inputVal = inputVal + sourceAlone + "</td></tr>";
					Zoho.inputArr[buildSubImageName] = sourceAlone;
					Zoho.labelArr[buildSubImageName] = Zoho.imgSrcLabel;
					
					if(altTxtReq) {
						var buildSubImageName = zohoElement.FieldName + Zoho.imgAlt;
						var altTxtAlone = Zoho.getIndividualField("zcalttext-",zohoElement.FieldName);
						inputVal = inputVal + "<tr><td>" + Zoho.imgAltLabel + "</td><td>" + altTxtAlone +"</td></tr>";
						Zoho.inputArr[buildSubImageName] = altTxtAlone;
						Zoho.labelArr[buildSubImageName] = Zoho.imgAltLabel;
						
					}
					
					if(imgLinkReq) {
						var buildSubImageName = zohoElement.FieldName + Zoho.imgLink;
						var linkAlone = Zoho.getIndividualField("zcfieldlink-",zohoElement.FieldName);
						inputVal = inputVal + "<tr><td>Link</td><td>" + linkAlone +"</td></tr>";
						Zoho.inputArr[buildSubImageName] = linkAlone;
						Zoho.labelArr[buildSubImageName] = "Link";					
					}

					if(imgTitleReq) {
						var buildSubImageName = zohoElement.FieldName + Zoho.imgTitle;
						var titleAlone = Zoho.getIndividualField("zctitle-",zohoElement.FieldName);
						inputVal = inputVal + "<tr><td>" + Zoho.imgTitleLabel + "</td><td>" + titleAlone +"</td></tr>";
						Zoho.inputArr[buildSubImageName] = titleAlone;
						Zoho.labelArr[buildSubImageName] = Zoho.imgTitleLabel;					
					}
					if(!altTxtReq && !imgLinkReq && !imgTitleReq) {
						inputVal = sourceAlone;
					}
					else {
						inputVal = inputVal + "</tbody></table>";
					
					}
				break;

				case Zoho.URL:
					var urlLinkNameReq = zohoElement.urlLinkNameReq;
					var urlTitleReq = zohoElement.urlTitleReq;

					inputVal = "<table><tbody><tr><td>" + Zoho.urlUrlLabel +"</td><td>";

					var buildSubUrlName = zohoElement.FieldName + Zoho.urlUrl;
					urlAlone = Zoho.getIndividualField("zcurl-",zohoElement.FieldName);
					inputVal = inputVal + urlAlone + "</td></tr>";
					Zoho.inputArr[buildSubUrlName] = urlAlone;
					Zoho.labelArr[buildSubUrlName] = Zoho.urlUrlLabel;
					
					if(urlLinkNameReq) {
						var buildSubUrlName = zohoElement.FieldName + Zoho.urlLink;
						var linkAlone = Zoho.getIndividualField("zclnkname-",zohoElement.FieldName);
						inputVal = inputVal + "<tr><td>" + Zoho.urlLinkLabel + "</td><td>" + linkAlone +"</td></tr>";
						Zoho.inputArr[buildSubUrlName] = linkAlone;
						Zoho.labelArr[buildSubUrlName] = Zoho.urlLinkLabel;
						
					}
					
					if(urlTitleReq) {
						var buildSubUrlName = zohoElement.FieldName + Zoho.urlTitle;
						var titleAlone = Zoho.getIndividualField("zctitle-",zohoElement.FieldName);
						inputVal = inputVal + "<tr><td>" + Zoho.urlTitleLabel + "</td><td>" + titleAlone +"</td></tr>";
						Zoho.inputArr[buildSubUrlName] = titleAlone;
						Zoho.labelArr[buildSubUrlName] = Zoho.urlTitleLabel;					
					}

					if(!urlLinkNameReq && !urlTitleReq) {
						inputVal = urlAlone;
					}
					else {
						inputVal = inputVal + "</tbody></table>";					
					}					

				break;

				case Zoho.CHECK_BOX:
					valueArray = new Array();
					if(zohoElement.Initial=="checked")
					{
						valueArray['checked'] = "checked";
					}
					valueArray['name'] = zohoElement.FieldName;
					valueArray['type'] = "checkbox";
					inputVal = Zoho.getHtmlForElement("input",valueArray);
				break;
				
				case Zoho.PICK_LIST: case Zoho.LIST:
					//select dropdown
					valueArray = new Array();
					if(zohoElement.Type == Zoho.LIST) {
						valueArray['multiple'] = "multiple";
					}
					valueArray['name'] = zohoElement.FieldName;
					inputVal = Zoho.getHtmlForElement("select",valueArray);
					if(typeof(zohoElement.Choices) == "object") {
						for(var choiceValue in zohoElement.Choices) {
							var elementOptions = zohoElement.Choices[choiceValue];
							if(elementOptions) {
								var choiceArrList = new Array();
								for(var elementOptionValue in elementOptions) {
									//alert(elementOptionValue.length);
									var choiceIntValue = elementOptionValue.substring(6,elementOptionValue.length);
									choiceArrList[parseInt(choiceIntValue)] = elementOptions[elementOptionValue];
								}
								var iterVal = 1;
								if(zohoElement.Type==Zoho.PICK_LIST) {
									choiceArrList[0] = "-Select-";
									iterVal = 0;
								}
								for(var i=iterVal; i<choiceArrList.length; i++) {
									if(zohoElement.Initial == choiceArrList[i])	{
										valueArray['selected'] = "selected";
									}
									inputVal = inputVal + Zoho.getHtmlForElement("option",valueArray) + choiceArrList[i] + "</option>";
								}
								inputVal = inputVal + "</select>";
							}
						}
					}

				break;
				
				case Zoho.RADIO: case Zoho.CHECK_BOXES:
					if(typeof(zohoElement.Choices) == "object") {
						for(var choiceValue in zohoElement.Choices) {
							var elementOptions = zohoElement.Choices[choiceValue];
							if(elementOptions) {
								inputVal='';
								var choiceArr = new Array();
								for(var elementOptionValue in elementOptions) {
									var choiceIntValue = elementOptionValue.substring(6,elementOptionValue.length);
									choiceArr[parseInt(choiceIntValue)] = elementOptions[elementOptionValue];
								}

								for(var i=1; i<choiceArr.length; i++) {
									valueArray = new Array();
									if(zohoElement.Type==Zoho.RADIO)	{
										//radio
										valueArray['type'] = "radio";
									}
									else	{
										//checkbox
										valueArray['type'] = "checkbox";
									}
									valueArray['id'] = "zoho-" + zohoElement.FieldName + ":" + i;
									valueArray['name'] = zohoElement.FieldName;
									valueArray['value'] = choiceArr[i];
									if(zohoElement.Initial == choiceArr[i])	{
										valueArray['checked'] = "checked";
									}
									if(valueArray['name'] == "how_did_you_find_us" && inputVal != '') {
										inputVal = inputVal + '<br>' + Zoho.getHtmlForElement("input",valueArray);								
										} else {
										inputVal = inputVal + Zoho.getHtmlForElement("input",valueArray);
									}
									valueArray = new Array();
									valueArray['id'] = "zoho-" + zohoElement.FieldName + ":" + i;
									valueArray['for'] = "zoho-" + zohoElement.FieldName + ":" + i;
									inputVal = inputVal + Zoho.getHtmlForElement("label",valueArray);
									
									inputVal = inputVal + choiceArr[i] + "</label> ";
									

								}
							}
						}
					}
					break;
				}

				if(zohoElement.Type==Zoho.PERCENTAGE)	{
					inputVal = inputVal + " %";
				}
				if(zohoElement.Type==Zoho.CURRENCY) {
					inputVal = inputVal + " " + zohoElement.CurrencyType;
				}
			Zoho.inputArr[zohoElement.FieldName] = inputVal;
			}
		}
	}
	if(jsonData.Captcha) {
		var tempCaptcha = '';
		valueArray = new Array();
		valueArray['id'] = Zoho.formId + Zoho.oldCaptTxt;
		valueArray['type'] = "hidden";
		valueArray['value'] = jsonData.Captcha;
		valueArray['name'] = Zoho.oldCaptTxt;
		tempCaptcha = tempCaptcha + Zoho.getHtmlForElement("input",valueArray);
		
		valueArray = new Array();
		valueArray['id'] = Zoho.formId + Zoho.captchaName;
		valueArray['type'] = "text";
		//valueArray['size'] = "20";
		valueArray['name'] = Zoho.captchaName;
		tempCaptcha = tempCaptcha + Zoho.getHtmlForElement("input",valueArray) + "  ";
		
		valueArray = new Array();
		//valueArray['id'] = ;
		valueArray['align'] = "absmiddle";
		valueArray['title'] = "Random word embeded in this image for Human Interactive Proof";
		valueArray['alt'] = "Human Interactive Proof image";
		var srcBuild = "http://" + Zoho.serverName + "/getcaptcha.do?captcha=" + jsonData.Captcha;
		valueArray['src'] = srcBuild;
		valueArray['name'] = Zoho.captchaImgName;
		Zoho.formCaptchaInput = tempCaptcha + Zoho.getHtmlForElement("img",valueArray);
		Zoho.formCaptchaLabel = "* Verification Code";
	}
	
}

Zoho.getHtmlForElement = function(tagName,valueArray) {
	var buildHtml = "<" + tagName;
	if(!valueArray['id']) {
		valueArray['id'] = "zoho-" + valueArray['name'];
	}
	for(var params in valueArray) {
		if(valueArray[params]!='') {
			buildHtml = buildHtml + ' ' + params + '=\"' + valueArray[params] + '\"' ;
			}
			
		valueArray[params] = '';
	}
	var endtag = ' class=\"formobject\" />';
	if(tagName == "textarea" || tagName == "label" || tagName == "select" || tagName == "option") {
		endtag = ' class=\"formobject\" >'
	}
	buildHtml = buildHtml + endtag;
	return(buildHtml);
}

Zoho.getIndividualField = function(preName,fieldName) {
	valueArray = new Array();
	valueArray['name'] = preName + fieldName;
	valueArray['type'] = "text";
	return Zoho.getHtmlForElement("input",valueArray);	
	
}

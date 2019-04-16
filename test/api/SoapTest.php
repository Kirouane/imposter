<?php
declare(strict_types=1);
namespace Imposter;


use PHPUnit\Framework\TestCase;

class SoapTest extends TestCase
{
    use ImposterTrait;

    /**
     * @test
     */
    public function soap()
    {
        $this->loadWsdl();

        $this
            ->openImposter(8081)
            ->withPath('/wsdl/soap/')
            ->returnBody(<<<XML
                <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
                 <soapenv:Body>
                 <hs:Message>
                 <hs:Say>Hello</hs:Say>
                 </hs:Message>
                 </soapenv:Body>
                </soapenv:Envelope>
XML
            )
            ->once()
            ->send();

        $client     = new \SoapClient('http://localhost:8081/wsdl', ["trace" => 1, "exception" => 1, 'cache_wsdl' => WSDL_CACHE_NONE]);
        self::assertSame('Hello', $client->__soapCall("displayHello", []));


        $this->closeImposers();
    }

    private function loadWsdl()
    {
        $this
            ->openImposter(8081)
            ->withPath('/wsdl')
            ->returnBody(<<<XML
                
                <definitions name = "Hello"
                   targetNamespace = "http://www.exemples.com/wsdl/Hello.wsdl"
                   xmlns = "http://schemas.xmlsoap.org/wsdl/"
                   xmlns:soap = "http://schemas.xmlsoap.org/wsdl/soap/"
                   xmlns:tns = "http://www.exemples.com/wsdl/Hello.wsdl"
                   xmlns:xsd = "http://www.w3.org/2001/XMLSchema">
                
                   <message name = "DisplayHelloRequest">
                      <part name = "prenom" type = "xsd:string"/>
                   </message>
                
                   <message name = "DisplayHelloResponse">
                      <part name = "hello" type = "xsd:string"/>
                   </message>
                
                   <portType name = "Hello_PortType">
                      <operation name = "displayHello">
                         <input message = "tns:DisplayHelloRequest"/>
                         <output message = "tns:DisplayHelloResponse"/>
                      </operation>
                   </portType>
                
                   <binding name = "Hello_Binding" type = "tns:Hello_PortType">
                      <soap:binding style = "rpc"
                         transport = "http://schemas.xmlsoap.org/soap/http"/>
                      <operation name = "displayHello">
                         <soap:operation soapAction = "displayHello"/>
                         <input>
                            <soap:body
                               encodingStyle = "http://schemas.xmlsoap.org/soap/encoding/"
                               namespace = "urn:exemples:helloservice"
                               use = "encoded"/>
                         </input>
                
                         <output>
                            <soap:body
                               encodingStyle = "http://schemas.xmlsoap.org/soap/encoding/"
                               namespace = "urn:exemples:helloservice"
                               use = "encoded"/>
                         </output>
                      </operation>
                   </binding>
                
                   <service name = "Hello_Service">
                      <port binding = "tns:Hello_Binding" name = "Hello_Port">
                         <soap:address
                            location = "http://localhost:8081/wsdl/soap/" />
                      </port>
                   </service>
                </definitions>
XML
            )
            ->once()
            ->send();
    }
}
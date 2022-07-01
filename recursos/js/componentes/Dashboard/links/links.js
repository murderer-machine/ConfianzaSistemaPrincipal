import React, { useState, useEffect } from 'react'
import { Container, Row, Col, Card, Form, Accordion, Alert, Image } from 'react-bootstrap'
import { CopyToClipboard } from 'react-copy-to-clipboard'
import Button from '@material-ui/core/Button'
const Links = () => {
    const [datos_envio, setdatos_envio] = useState({
        id_empresa: '',
        ramo: '',
        url: '',
        usuario: '',
        password: '',
        observacion: '',
    })
    const [datos_mostrar, setdatos_mostrar] = useState([])
    const CambiarInput = (e) => {
        setdatos_envio({
            ...datos_envio,
            [e.target.id]: e.target.value
        })
    }
    const AgregarUrl = (e) => {
        e.preventDefault()
        fetch('/Links/agregar', {
            method: 'POST',
            body: JSON.stringify(datos_envio),
            headers: {
                'Content-Type': 'application/json'
            }
        })
            .then(res => res.json())
            .catch(error => console.error('Error:', error))
            .then(response => {
                if (response == 0) {
                    alert('Se agrego con éxito')
                    setdatos_envio({
                        id_empresa: '',
                        ramo: '',
                        url: '',
                        usuario: '',
                        password: '',
                        observacion: '',
                    })
                }
                if (response == 1) {
                    alert('Ocurrió un error')
                }
            })
    }
    const EditarUrl = (e) => {
        e.preventDefault()
        fetch(`/Links/editar/${id_editar}`, {
            method: 'POST',
            body: JSON.stringify(datos_envio),
            headers: {
                'Content-Type': 'application/json'
            }
        })
            .then(res => res.json())
            .catch(error => console.error('Error:', error))
            .then(response => {
                if (response == 0) {
                    alert('Se edito con éxito')
                    setdatos_envio({
                        id_empresa: '',
                        ramo: '',
                        url: '',
                        usuario: '',
                        password: '',
                        observacion: '',
                    })
                    setcondicion_editar(false)
                    setid_editar(0)
                }
                if (response == 1) {
                    alert('Ocurrió un error')
                }
            })
    }
    useEffect(() => {
        fetch('/Links/mostrar')
            .then(response => response.json())
            .then(data => setdatos_mostrar(data))
    }, [datos_envio])
    const [condicion_editar, setcondicion_editar] = useState(false)
    const [id_editar, setid_editar] = useState(0)
    const Editar = (datos, id_empresa) => {
        setcondicion_editar(true)
        setid_editar(datos.id)
        setdatos_envio({
            id_empresa: id_empresa,
            ramo: datos.ramo,
            url: datos.url,
            usuario: datos.usuario,
            password: datos.password,
            observacion: datos.observacion,
        })
    }
    const Cancelar = () => {
        setcondicion_editar(false)
        setdatos_envio({
            id_empresa: '',
            ramo: '',
            url: '',
            usuario: '',
            password: '',
            observacion: '',
        })
    }
    return (
        <>
            <Container>
                <Row>
                    <Col xs={12} lg={4}>
                        <Card className="p-4">
                            <Form onSubmit={!condicion_editar ? AgregarUrl : EditarUrl}>
                                <Form.Group controlId="id_empresa">
                                    <Form.Label>Empresa</Form.Label>
                                    <Form.Control size="sm" onChange={CambiarInput} value={datos_envio.id_empresa} />
                                </Form.Group>
                                <Form.Group controlId="ramo">
                                    <Form.Label>Ramo</Form.Label>
                                    <Form.Control size="sm" onChange={CambiarInput} value={datos_envio.ramo} />
                                </Form.Group>
                                <Form.Group controlId="url">
                                    <Form.Label>Url</Form.Label>
                                    <Form.Control size="sm" onChange={CambiarInput} value={datos_envio.url} />
                                </Form.Group>

                                <Form.Group controlId="usuario">
                                    <Form.Label>Usuario</Form.Label>
                                    <Form.Control size="sm" onChange={CambiarInput} value={datos_envio.usuario} />
                                </Form.Group>

                                <Form.Group controlId="password">
                                    <Form.Label>Contraseña</Form.Label>
                                    <Form.Control size="sm" onChange={CambiarInput} value={datos_envio.password} />
                                </Form.Group>

                                <Form.Group controlId="observacion">
                                    <Form.Label>Observación</Form.Label>
                                    <Form.Control as="textarea" rows={3} size="sm" onChange={CambiarInput} value={datos_envio.observacion} />
                                </Form.Group>

                                <Form.Group >
                                    <Button variant="contained" color="primary" type="submit" size="small">{!condicion_editar ? 'Agregar' : 'Editar'}</Button>
                                    {condicion_editar ? (<>
                                        {' '}
                                        <Button variant="contained" color="primary" type="button" size="small" onClick={Cancelar}>Cancelar</Button>
                                    </>) : (<></>)}
                                </Form.Group>
                            </Form>
                        </Card>
                    </Col>
                    <Col xs={12} lg={8}>
                        <Accordion >
                            {datos_mostrar.map((dato, i) => (
                                <Card key={i + 1} className="my-2">
                                    <Accordion.Toggle as={Card.Header} eventKey={i + 1} style={{ cursor: 'pointer' }}>
                                        <h5 className="d-inline">({dato.id_empresa.id})</h5><h6>{dato.id_empresa.nombre.toUpperCase()} - {dato.id_empresa.ruc.toUpperCase()}</h6>
                                        <Image src={`/recursos/img/logos_empresas_seguros/${dato.id_empresa.logo}`} style={{ width: '100px' }} />
                                    </Accordion.Toggle>
                                    <Accordion.Collapse eventKey={i + 1}>
                                        <Card.Body>
                                            {
                                                dato.datos.map((item, i_i) => (
                                                    <div key={i_i}>
                                                        <Alert variant="secondary">
                                                            <h6>{item.ramo.toUpperCase()}</h6>
                                                            <b>Url : </b><a href={item.url} target="_blank">{item.url}</a><br />
                                                            <b>Usuario : </b>{item.usuario}<CopyToClipboard text={item.usuario}><span style={{ color: '#2A427B', fontWeight: 'bold', cursor: 'pointer' }} className="ml-2">Copiar</span></CopyToClipboard><br />
                                                            <b>Contraseña : </b>{item.password}<CopyToClipboard text={item.password}><span style={{ color: '#2A427B', fontWeight: 'bold', cursor: 'pointer' }} className="ml-2">Copiar</span></CopyToClipboard><br />
                                                            <b>Observación : </b>{item.observacion}<br />
                                                            {item.fecha_hora}<br />
                                                            <Button variant="contained" color="primary" size="small" onClick={() => { Editar(item, dato.id_empresa.id) }}>Editar</Button>{' '}
                                                            <Button variant="contained" color="primary" size="small" onClick={() => { Editar(item, dato.id_empresa.id) }}>Eliminar</Button>
                                                        </Alert>
                                                    </div>
                                                ))
                                            }
                                        </Card.Body>
                                    </Accordion.Collapse>
                                </Card>
                            ))}
                        </Accordion>
                    </Col>
                </Row>
            </Container>
        </>
    )
}
export default Links

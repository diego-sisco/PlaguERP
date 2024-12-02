import React, {useState, useEffect} from 'react';
import {ScrollView, Text, View, Button} from 'react-native';
import {styles} from '../styles/app';
import {TablePests} from '../components/tables/ChemicalApplication/TablePests';
import {TableResources} from '../components/tables/ChemicalApplication/TableResources';
import {ModalPest} from '../components/modals/ChemicalApplication/Pest';
import {ModalResource} from '../components/modals/ChemicalApplication/Resource';
import {readFile, writeFile, cleanFile} from '../functions/FileHandling';
import {file_paths} from '../functions/FilePath';
import {ServiceType} from '../types/Service';
import {PestType} from '../types/Pest';
import {ProductType} from '../types/Product';
import {OrderType} from '../types/Order';
import {ApplicationMethodType} from '../types/ApplicationMethod';
import {Resource} from '../types/Resource';
import {ServicePest} from '../types/ServicePest';
import {ChemicalApplication} from '../types/ChemicalApplication';
import {Navigation} from 'react-native-navigation';

export const Service = (props: any) => {
  const orderID: number = props.orderID;
  const [services, setServices] = useState<ServiceType[]>([]);
  const [pests, setPests] = useState<PestType[]>([]);
  const [products, setProducts] = useState<ProductType[]>([]);
  const [appMethods, setAppMethods] = useState<ApplicationMethodType[]>([]);

  const [selectedPests, setSelectedPests] = useState<ServicePest[]>([]);
  const [selectedResources, setSelectedResources] = useState<Resource[]>([]);

  const [pestModalVisible, setPestModalVisible] = useState<boolean>(false);
  const [resourceModalVisible, setResourceModalVisible] =
    useState<boolean>(false);

  useEffect(() => {
    const getData = async () => {
      try {
        let pestIds: number[] = [];
        const orders = await readFile(file_paths.orders);
        const fetchedServices: ServiceType[] = await readFile(
          file_paths.catalog.services,
        );
        const fetchedPests: PestType[] = await readFile(
          file_paths.catalog.pests,
        );
        const fetchedProducts: ProductType[] = await readFile(
          file_paths.catalog.products,
        );
        const fetchedAppMethods: ApplicationMethodType[] = await readFile(
          file_paths.catalog.applicationMethods,
        );
        const fetchedChemicalApplications: ChemicalApplication[] =
          await readFile(file_paths.data.chemicalApplications);

        let order = orders.find((item: OrderType) => item.id == orderID);
        if (order) {
          const found_services: ServiceType[] = fetchedServices.filter(
            (item: ServiceType) => order.servicesID.includes(item.id),
          );
          const pestIds = [
            ...new Set(
              found_services.flatMap((service: ServiceType) => service.pestsID),
            ),
          ];
          setServices(found_services);
          setPests(
            fetchedPests.filter((pest: PestType) => pestIds.includes(pest.id)),
          );
          setProducts(fetchedProducts);
          setAppMethods(fetchedAppMethods);

          if (selectedPests.length <= 0 && selectedResources.length <= 0) {
            fetchedChemicalApplications.forEach(application => {
              // Procesar pests
              const updatedPests: ServicePest[] = application.pests.map(
                pest => ({
                  key: pest.key,
                  values: pest.values.map(item => ({
                    id: item.id,
                    value: item.value,
                  })),
                }),
              );

              // Procesar resources
              const updatedResources: Resource[] = application.resources.map(
                resource => ({
                  service_id: resource.service_id,
                  array_ids: resource.array_ids.map(item => ({
                    product_id: item.product_id,
                    appMethod_id: item.appMethod_id,
                    value: item.value,
                  })),
                }),
              );

              // Actualizar los estados
              setSelectedPests(prevPests => [...prevPests, ...updatedPests]);
              setSelectedResources(prevResources => [
                ...prevResources,
                ...updatedResources,
              ]);
            });
          }
        }
      } catch (error) {
        console.error('Error fetching data:', error);
      }
    };

    getData();
  }, [orderID]);

  const handleAddPest = (newPests: ServicePest[]) => {
    setSelectedPests(newPests);
    setPestModalVisible(false); // Cierra el modal después de agregar la plaga
  };

  const handleAddResource = (newResources: Resource[]) => {
    setSelectedResources(newResources);
    setResourceModalVisible(false); // Cierra el modal después de agregar la plaga
  };

  const storeData = async () => {
    let fetchedChemicalApplications: ChemicalApplication[] = await readFile(
      file_paths.data.chemicalApplications,
    );

    if (fetchedChemicalApplications.length > 0) {
      let foundedChemicalApplications: ChemicalApplication | undefined =
        fetchedChemicalApplications.find(
          (chemicalApp: ChemicalApplication) => chemicalApp.order_id == orderID,
        );

      if (foundedChemicalApplications) {
        foundedChemicalApplications.resources = selectedResources;
        foundedChemicalApplications.pests = selectedPests;
      } else {
        fetchedChemicalApplications.push({
          order_id: orderID,
          resources: selectedResources,
          pests: selectedPests,
        });
      }
    } else {
      fetchedChemicalApplications.push({
        order_id: orderID,
        resources: selectedResources,
        pests: selectedPests,
      });
    }

    await writeFile(
      fetchedChemicalApplications,
      file_paths.data.chemicalApplications,
    );

    Navigation.push(props.viewId, {
      component: {
        name: props.viewDetails,
        passProps: {
          redirectID: props.viewId,
          orderID: orderID,
        },
        options: {
          topBar: {
            title: {
              text: 'Detalles',
            },
          },
        },
      },
    });
  };

  return (
    <ScrollView style={styles.root}>
      <View>
        <View style={styles.buttons}>
          <Button
            title="Agregar Plaga"
            color="#000000"
            onPress={() => {
              setPestModalVisible(true);
            }}
          />
          <Button
            title="Agregar Químico"
            color="#6610f2"
            onPress={() => {
              setResourceModalVisible(true);
            }}
          />
        </View>

        <ModalPest
          services={services}
          pests={pests}
          selectedPests={selectedPests}
          modalVisible={pestModalVisible}
          setModalVisible={setPestModalVisible}
          setSelectedPests={handleAddPest} // Usa la función para agregar plagas
        />

        <ModalResource
          services={services}
          products={products}
          appMethods={appMethods}
          selectedResources={selectedResources}
          modalVisible={resourceModalVisible}
          setModalVisible={setResourceModalVisible}
          setResource={handleAddResource} // Usa la función para agregar plagas
        />

        {services.map((service: ServiceType) => (
          <View key={service.id} style={styles.section}>
            <View style={styles.sectionTitle}>
              <Text style={styles.title}>{service.name}</Text>
            </View>

            <Text style={styles.subtitle}>Plagas: </Text>
            <TablePests
              selectedPests={selectedPests}
              pests={pests}
              serviceId={service.id}
              setSelectedPests={setSelectedPests}
            />
            <Text style={styles.subtitle}>Químicos: </Text>
            <TableResources
              selectedResources={selectedResources}
              products={products}
              appMethods={appMethods}
              serviceId={service.id}
              setSelectedResources={setSelectedResources}
            />
          </View>
        ))}
      </View>
      <View style={styles.section}>
        <View style={styles.button}>
          <Button
            title="Guardar servicios"
            color="#198754"
            onPress={() => {
              storeData();
            }}
          />
        </View>
      </View>
    </ScrollView>
  );
};
